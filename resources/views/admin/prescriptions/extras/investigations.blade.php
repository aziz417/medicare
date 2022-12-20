<div class="modal fade" id="patient-investigations" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Patient Investigations</h5>
            </div>

            <div class="modal-body p-0">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th width="25%">Title</th>
                        <th>Details</th>
                        <th width="20%">Last Update</th>
                        <th width="5%"></th>
                    </tr>
                    </thead>
                    <tbody id="investigation-table">
                    <tr>
                        <td class="text-center" colspan="4">
                            <div class="mr-2 spinner-border text-primary" role="status"><span
                                        class="sr-only">Loading...</span></div>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">
                            <hr class="w-100 mt-5 m-0">
                            <h4 data-toggle="collapse" data-target="#add-investigations"
                                class="clickable col-md-12 my-2 cursor-pointer btn btn-outline-info w-auto m-0 btn-outline">
                                Add New Item</h4>
                            <form id="add-investigations" action="{{ route('admin.investigations.store') }}"
                                  method="POST" class="collapse form-inline row justify-content-between">
                                @csrf
                                <input id="investigations-user-id" type="hidden" name="user_id"
                                       value="{{ $user_id ?? null }}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Investigation Title</label>
                                        <input name="title" placeholder="Title" type="text"
                                               class="form-control form-control-sm w-100" list="investigations-list"
                                               autocomplete="off" required>
                                        <datalist id="investigations-list">
                                            @foreach(config('system.investigations', []) as $iName)
                                                <option>{{ $iName }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Details</label>
                                        <input name="details" placeholder="Details" type="text"
                                               class="form-control form-control-sm w-100" required>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label class="control-label">Date</label>
                                            <input name="data_date[]" placeholder="Date" type="date"
                                                   class="form-control form-control-sm w-100">
                                        </div>
                                        <div class="form-group col-6">
                                            <label class="control-label">Report</label>
                                            <textarea name="data_details[]" cols="30" rows="1"
                                                      class="form-control form-control-sm w-100"
                                                      placeholder="Investigation result"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-2 text-right">
                                    <button id="inv-submit" type="submit" class="btn btn-sm btn-primary">
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
                <div id="updateReport"></div>

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
        function editFunction(id) {
            $.get("{{ route('admin.investigation.edit') }}", {id: id}, function (response) {
                $("#updateReport").append(response)
            })
        }

        if (typeof (HISTORY_USER) === 'undefined') {
            let HISTORY_USER = "{{$user_id ?? null}}";
        } else {
            HISTORY_USER = "{{$user_id ?? null}}";
        }
        (function ($) {
            const serializeData = function (items) {
                return items.map(item => (
                    `<tr><td>${item.date || ''}</td><td>${item.details || ''}</td></tr>`
                )).join('')
            }

            function saveNew(item) {
                if (!$(item).data('link')) {
                    return false
                }
                console.log($(item).data('link'));
                $(item).ajaxForm({
                    'url': $(item).data('link'),
                    clearForm: true,
                    beforeSubmit: function () {
                        $(item).find('button').prop('disabled', true);
                    },
                    success: function (response, status) {
                        console.log(response, status);
                        $(item).find('button').prop('disabled', false);
                        if (response.status && status === 'success') {
                            $(item).parents('table').find('.inv-items').html(serializeData(response.data))
                        }
                        $(item).find('button').prop('disabled', false);
                    },
                    //timeout:   3000
                });
                return false;
            }

            const add_form = function (link) {
                return `<tfoot><tr><td class="border-top" colspan="4"><form class="save-new form-inline d-flex justify-content-between" data-link="${link}" action="javascript:void(0)" method="POST"> @method('PUT')@csrf <input name="date" placeholder="Date" type="date" class="form-control bg-transparent form-control-sm" required><input name="details" placeholder="Description" type="text" class="form-control bg-transparent form-control-sm" required><button type="submit" class="btn btn-sm btn-warning">Save</button></form></td></tr></tfoot>`;
            }
            $('#patient-investigations').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                // if( HISTORY_USER === "" ){
                //     alert("Choose a patient first!")
                //     return false;
                // }
                $('#investigations-user-id').val(HISTORY_USER);

                if (HISTORY_USER) {
                    $.get(`{{ route('admin.investigations.index') }}?user=${HISTORY_USER}`, function (response) {
                        let DATA = ['<tr><td colspan="4">No Data Found!</td></tr>'];
                        if (response.status && response.data.length > 0) {
                            DATA = response.data.map(item => (
                                `<tr data-toggle="collapse" data-target="#accordion-inv-${item.id}" class="clickable"><td>${item.title}</td><td>${item.details}</td><td>${item.updated_at}</td><td class="text-right">
<button type="button" onclick="editFunction(${item.id})" data-id="${item.id}" id="editbutton" class="btn btn-info btn-sm  px-1 mb-1 rounded-pill edit-btn">
Edit
</button>
<button type="button" data-id="${item.id}" class="btn btn-danger btn-sm p-1 rounded-pill inv-remove-btn"><span class="btn-icon icofont-trash"></span></button>
</td></tr><!-- Separator --><tr><td colspan="4" id="data-${item.id}"><div id="accordion-inv-${item.id}" class="collapse"><table class="table table-sm table-info"><thead><tr><th>Date</th><th>Report</th></tr></thead><tbody class="inv-items">${serializeData(item.data)}</tbody>${add_form(item.update_link)}</table></div></td></tr>`
                            ));
                        }
                        $('#patient-investigations #investigation-table').html(DATA.join(''))
                        $('#patient-investigations #investigation-table').find('.save-new').each(function (i, item) {
                            saveNew(item)
                        })
                    })
                }
            })


            $('#add-investigations').ajaxForm({
                url: "{{ route('admin.investigations.store') }}",
                clearForm: true,
                beforeSubmit: function () {
                    $('#inv-submit .loader').toggleClass('d-none');
                },
                success: function (response, status) {
                    if (response.status && status === 'success') {
                        let {title, details, updated_at, id, update_link, data} = response.data;
                        $('#investigation-table').prepend(`<tr  data-toggle="collapse" data-target="#accordion-inv-${id}" class="clickable investigation-item-${id}"><td>${title}</td><td>${details}</td><td>${updated_at}</td><td class="text-right">
<button type="button" onclick="editFunction(${id})" data-id="${id}" class="btn btn-info btn-sm  px-1 mb-1 rounded-pill edit-btn">
Edit
</button>
<button type="button" data-id="${id}" class="btn btn-danger btn-sm p-1 rounded-pill inv-remove-btn"><span class="btn-icon icofont-trash"></span></button>
</td></tr><!-- Separator --><tr class="investigation-item-${id}"><td id="data-${id}" colspan="4"><div id="accordion-inv-${id}" class="collapse"><table class="table table-sm table-info"><thead><tr><th>Date</th><th>Report</th></tr></thead><tbody class="inv-items">${serializeData(data)}</tbody>${add_form(update_link)}</table></div></td></tr>`);

                        $(`#data-${id}`).find('.save-new').each(function (i, item) {
                            saveNew(item)
                        })
                    }
                    $('#inv-submit .loader').addClass('d-none');
                },
                //timeout:   3000
            })
            ;

            $("#inv-submit").on("click", function () {
                var data = $('#add-investigation-update').serialize()
                console.log(data)
            })
            $('#add-investigation-update').ajaxForm({
                url: "{{ route('admin.investigation.customUpdate') }}",
                clearForm: true,
                beforeSubmit: function () {
                    $('#inv-submit .loader').toggleClass('d-none');
                },
                success: function (response, status) {
                    if (response.status && status === 'success') {
                        $(".investigation-item-" + id).remove();
                        let {title, details, updated_at, id, update_link, data} = response.data;
                        $('#investigation-table').prepend(`<tr data-toggle="collapse" data-target="#accordion-inv-${id}" class="clickable investigation-item-${id}"><td>${title}</td><td>${details}</td><td>${updated_at}</td><td class="text-right">
<button type="button" data-id="${id}" class="btn btn-info btn-sm  px-1 mb-1 rounded-pill edit-btn">
Edit
</button>
<button type="button" data-id="${id}" class="btn btn-danger btn-sm p-1 rounded-pill inv-remove-btn"><span class="btn-icon icofont-trash"></span></button>
</td></tr><!-- Separator --><tr class="investigation-item-${id}"><td id="data-${id}" colspan="4"><div id="accordion-inv-${id}" class="collapse"><table class="table table-sm table-info"><thead><tr><th>Date</th><th>Report</th></tr></thead><tbody class="inv-items">${serializeData(data)}</tbody>${add_form(update_link)}</table></div></td></tr>`);

                        $(`#data-${id}`).find('.save-new').each(function (i, item) {
                            saveNew(item)
                        })
                    }
                    $('#inv-submit .loader').addClass('d-none');
                },
                //timeout:   3000
            });


            // $(".edit-btn").on('click', function (){
            //     console.log('fgdfhfg')
            // })

            $(document).on('click', '.inv-remove-btn', function () {
                let $this = $(this);
                let __url = function (id) {
                    let link = "{{ route('admin.investigations.destroy', 0) }}";
                    return `${link.slice(0, -1)}${id}`
                }
                if (confirm("Are you sure?")) {
                    $.ajax({
                        method: "POST",
                        url: __url($this.data('id')),
                        data: {_token: __App.csrf_token, _method: 'DELETE'},
                        success: function (response) {
                            if (response.status) {
                                $($this.parents('tbody').find('#data-' + $this.data('id'))).remove();
                                $this.parents('tr').remove()
                            }
                        }
                    })
                }
            })
        })
            (jQuery)
    </script>
@endpush
