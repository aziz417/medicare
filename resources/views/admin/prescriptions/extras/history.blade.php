<div class="modal fade" id="patient-history" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Health History</h5>
            </div>
            <div class="modal-body p-0">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th width="25%">Title</th>
                        <th>Details</th>
                        <th width="20%">Created at</th>
                        <th width="5%"></th>
                    </tr>
                    </thead>
                    <tbody id="history-table">
                    <tr>
                        <td class="text-center" colspan="4">
                            <div class="mr-2 spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr class="d-none" id="updateFrom">
                        <td colspan="4">
                            <form id="add-history-update" method="post" action="{{ route('admin.history.update') }}"
                                  class="mt-5 form-inline row justify-content-between">
                                @csrf
                                @method('put')
                                <input id="history-update-user-id" type="hidden" name="user_id">
                                <hr class="w-100 m-0">
                                <h4 class="col-md-12 my-2">Update this item</h4>
                                <div class="col-md-4">
                                    <input name="title" placeholder="Title" id="update-title-set" type="text" class="form-control w-100"
                                           list="patient-histories" autocomplete="off">
                                    <datalist id="patient-histories">
                                        @foreach(config('system.patient_history', []) as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-6">
                                    <textarea id="report-update" name="details" cols="30"  rows="1" class="form-control w-100"
                                              placeholder="Write Something..."></textarea>
                                </div>
                                <div class="col-md-2 text-right">
                                    <button id="history-submit-update" type="submit" class="btn btn-sm btn-primary">
                                        <div class="mr-2 loader spinner-border-sm spinner-border text-white d-none"
                                             role="status"><span class="sr-only">Loading...</span></div>
                                        Update
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <form id="add-history" action="{{ route('admin.history.store') }}" method="POST"
                                  class="mt-5 form-inline row justify-content-between">
                                @csrf
                                <input id="history-user-id" type="hidden" name="user_id" value="{{ $user_id ?? null }}">
                                <hr class="w-100 m-0">
                                <h4 class="col-md-12 my-2">Add New Item</h4>
                                <div class="col-md-4">
                                    <input name="title" placeholder="Title" type="text" class="form-control w-100"
                                           list="patient-histories" autocomplete="off">
                                    <datalist id="patient-histories">
                                        @foreach(config('system.patient_history', []) as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="details" cols="30" rows="1" class="form-control w-100"
                                              placeholder="Write Something..."></textarea>
                                </div>
                                <div class="col-md-2 text-right">
                                    <button id="history-submit" type="submit" class="btn btn-sm btn-primary">
                                        <div class="mr-2 loader spinner-border-sm spinner-border text-white d-none"
                                             role="status"><span class="sr-only">Loading...</span></div>
                                        Add New
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <div class="actions">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer')
    <script type="text/javascript" src="{{ asset('assets/libs/jquery.form.js') }}"></script>
    <script type="text/javascript">
        let HISTORY_USER = "{{$user_id ?? null}}";
        (function ($) {
            $('#patient-history').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                // if( HISTORY_USER === "" ){
                //     alert("Choose a patient first!")
                //     return false;
                // }
                $.get(`{{ route('admin.history.index') }}?user=${HISTORY_USER}`, function (response) {
                    let DATA = ['<tr><td colspan="3">No Data Found!</td></tr>'];
                    if (response.status && response.data.length > 0) {
                        DATA = response.data.map(item => (
                            `<tr class="history-item-${item.id}">
<td width="25%">${item.title}</td>
<td>${item.details}</td>
<td>${item.created_at}</td>
<td class="text-right">
<button type="button" data-id="${item.id}" class="btn btn-info btn-sm  px-1 mb-1 rounded-pill edit-btn">
Edit
</button>
<button type="button" data-id="${item.id}" class="btn btn-danger btn-sm p-1 rounded-pill history-remove-btn">
<span class="btn-icon icofont-trash"></span>
</button>
</td>
</tr>`
                        ));
                    }
                    $('#patient-history #history-table').html(DATA.join(''))
                })
            })
            $('#add-history').ajaxForm({
                url: "{{ route('admin.history.store') }}",
                clearForm: true,
                beforeSubmit: function () {
                    $('#history-submit .loader').toggleClass('d-none');
                },
                success: function (response, status) {
                    if (response.status && status === 'success') {
                        let {title, details, id, created_at} = response.data;
                        $('#history-table').prepend(`<tr><td width="25%">${title}</td><td>${details}</td><td>${moment(created_at).format('DD MMM, YYYY')}</td><td class="text-right">
<button type="button" data-id="${id}" class="btn btn-info btn-sm  px-1 mb-1 rounded-pill edit-btn">
Edit
</button>
<button type="button" data-id="${id}" class="btn btn-danger btn-sm p-1 rounded-pill history-remove-btn"><span class="btn-icon icofont-trash"></span></button>
</td></tr>`)
                    }
                    $('#history-submit .loader').addClass('d-none');
                },
                //timeout:   3000
            });

            $('#add-history-update').ajaxForm({
                url: "{{ route('admin.history.update') }}",
                clearForm: true,

                beforeSubmit: function () {
                    $('#history-submit-update .loader').toggleClass('d-none');
                },
                success: function (response, status) {
                    if (response.status && status === 'success') {
                        let {title, details, id, created_at} = response.data;
                        $(".history-item-"+id).remove();
                        $('#history-table').prepend(`<tr class="history-item-${id}"><td width="25%">${title}</td><td>${details}</td><td>${moment(created_at).format('DD MMM, YYYY')}</td><td class="text-right">
<button type="button" data-id="${id}" class="btn btn-info btn-sm  px-1 mb-1 rounded-pill edit-btn">
Edit
</button>
<button type="button" data-id="${id}" class="btn btn-danger btn-sm p-1 rounded-pill history-remove-btn"><span class="btn-icon icofont-trash"></span></button>
</td></tr>`)
                    }
                    $('#history-submit-update .loader').addClass('d-none');
                },
                //timeout:   3000
            });
            $(document).on('click', '.history-remove-btn', function () {
                let $this = $(this);
                let __url = function (id) {
                    let link = "{{ route('admin.history.destroy', 0) }}";
                    return `${link.slice(0, -1)}${id}`
                }
                if (confirm("Are you sure?")) {
                    $.ajax({
                        method: "POST",
                        url: __url($this.data('id')),
                        data: {_token: __App.csrf_token, _method: 'DELETE'},
                        success: function (response) {
                            if (response.status) {
                                $this.parents('tr').remove()
                            }
                        }
                    })
                }
            })

            $(document).on('click', '.edit-btn', function () {
                let $this = $(this);
                let __url = function (id) {
                    let link = `{{ route('admin.history.edit') }}?id=${id}`;
                    return `${link}`
                }

                $.ajax({
                    method: "get",
                    url: __url($this.data('id')),
                    data: {_token: __App.csrf_token, _method: 'get'},
                    success: function (response) {
                        if (response.status) {
                            $("#updateFrom").removeClass("d-none")
                            $("#report-update").val(response.data.details)
                            $("#history-update-user-id").val(response.data.id)
                            $("#update-title-set").val(response.data.title)
                        }
                    }
                })
            })
        })(jQuery)
    </script>
@endpush
