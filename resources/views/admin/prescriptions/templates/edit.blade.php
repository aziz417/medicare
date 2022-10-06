@extends('layouts.app')
@section('title', 'Edit Prescription Template')

@section('content')
<header class="page-header">
    <h2 class="page-title mt-0 mb-2">Edit Prescriptions Template</h2>
</header>
<hr class="my-2"/>
<div class="page-content mt-2">
    <div class="row">
        <div class="col-md-12 mb-5">
            <form method="POST" action="{{ route('admin.prescriptions-templates.update', ['prescriptions_template'=>$template->id]) }}">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mt-0">Prescription Info
                        </h4>
                        <div class="prescription-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="title">Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Write Something..." value="{{ $template->title ?? old('title') }}"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="concern">Major Concern</label>
                                        <textarea class="form-control" name="chief_complain" id="concern" cols="30" rows="1" placeholder="Write Something...">{{ $template->chief_complain ?? old('chief_complain') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5>Medicines</h5>
                        <div id="medicines">
                            <table class="table medicines-table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Dosing</th>
                                        <th>Days</th>
                                        <th>Instruction</th>
                                        <th>
                                            <a id="clone-btn" href="javascript:void(0);" class="btn btn-sm btn-outline-primary rounded-pill p-1"><span class="btn-icon icofont-plus"></span></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="medicine-items">
                                    @forelse($template->medicines ?? [] as $medicine)
                                    <tr>
                                        <td>
                                            <div class="typeahead__container cancel">
                                                <div class="typeahead__field">
                                                    <div class="typeahead__query">
                                                        <input class="form-control form-control-sm medicine-search" type="search" placeholder="Medicine Name" autocomplete="off" name="medicines[]" 
                                                        value="{{$medicine['name'] ?? ''}}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm medicine-type" type="text" name="type[]" value="{{$medicine['type'] ?? ''}}" placeholder="Medicine Type" list="mtypes">
                                            <datalist id="mtypes">
                                                @foreach(config('system.medicine_types',[]) as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </datalist>
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm" type="text" name="quantity[]" value="{{$medicine['quantity'] ?? ''}}" placeholder="Dosing" list="dosing" autocomplete="off">
                                            <datalist id="dosing">
                                                <option value="1+1+1">1+1+1 - Daily</option>
                                                <option value="1+0+1">1+0+1 - Daily</option>
                                                <option value="1+1+0">1+1+0 - Daily</option>
                                                <option value="1+0+0">1+0+0 - Daily</option>
                                                <option value="0+1+1">0+1+1 - Daily</option>
                                                <option value="0+0+1">0+0+1 - Daily</option>
                                                <option value="0+1+0">0+1+0 - Daily</option>
                                            </datalist>
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm" type="text" name="days[]" value="{{$medicine['days'] ?? ''}}" placeholder="Days">
                                        </td>
                                        <td>
                                            <textarea name="instruction[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Instruction">{{ $medicine['instruction'] ?? '' }}</textarea>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm p-1 rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>
                                            <div class="typeahead__container cancel">
                                                <div class="typeahead__field">
                                                    <div class="typeahead__query">
                                                        <input class="form-control form-control-sm medicine-search" type="search" placeholder="Placeholder" autocomplete="off" name="medicines[]" 
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm medicine-type" type="text" name="type[]" placeholder="Medicine Type">
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm" type="text" name="quantity[]" placeholder="Quantity">
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm" type="text" name="days[]" placeholder="Days">
                                        </td>
                                        <td>
                                            <textarea name="instruction[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Instruction"></textarea>
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <h5>Diagnosis</h5>
                        <div id="diagnosis">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th>
                                            <a id="diagnosis-clone-btn" href="javascript:void(0);" class="btn btn-sm btn-outline-primary rounded-pill p-1"><span class="btn-icon icofont-plus"></span></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="diagnosis-items">
                                    @forelse($template->investigations ?? [] as $item)
                                    <tr>
                                        <td>
                                            <input class="form-control form-control-sm medicine-type" type="text" name="diagnosis_title[]" value="{{$item['title']??''}}" placeholder="Diagnosis Name/Title">
                                        </td>
                                        <td>
                                            <textarea name="diagnosis_details[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Details...">{{ $item['details']??'' }}</textarea>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm p-1 rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>
                                            <input class="form-control form-control-sm medicine-type" type="text" name="diagnosis_title[]" placeholder="Diagnosis Name/Title">
                                        </td>
                                        <td>
                                            <textarea name="diagnosis_details[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Details..."></textarea>
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="form-button float-right mt-5">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('footer')
<script type="text/javascript">
    const NEW_TEMPLATE = `<tr><td><div class="typeahead__container cancel"><div class="typeahead__field"><div class="typeahead__query"><input class="form-control form-control-sm medicine-search" type="search" placeholder="Medicine Name" autocomplete="off" name="medicines[]" /></div></div></div></td><td><input class="form-control form-control-sm medicine-type" type="text" name="type[]" list="mtypes" placeholder="Medicine Type"></td><td><input class="form-control form-control-sm" type="text" name="quantity[]" placeholder="Dosing" list="dosing" autocomplete="off"></td><td><input class="form-control form-control-sm" type="text" name="days[]" placeholder="Days"></td><td><textarea name="instruction[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Instruction"></textarea></td><td><button type="button" class="btn btn-danger btn-sm p-1 rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></td></tr>`;
    const NEW_DIAGNOSIS_TEMPLATE = `<tr><td><input class="form-control form-control-sm medicine-type" type="text" name="diagnosis_title[]" placeholder="Diagnosis Name/Title"></td><td><textarea name="diagnosis_details[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Details..."></textarea></td><td><button type="button" class="btn btn-danger btn-sm p-1 rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></td></tr>`;
    (function($){
        $('#clone-btn').on('click', function(){
            var newItem = $(NEW_TEMPLATE);
            seaarchMedicine(newItem.find('.medicine-search'))
            $('#medicine-items').append(newItem);
        });
        $('#diagnosis-clone-btn').on('click', function(){
            var newItem = $(NEW_DIAGNOSIS_TEMPLATE);
            $('#diagnosis-items').append(newItem);
        });
        $(document).on('click', '.item-remove-btn', function(){
            $(this).parents('tr').remove();
        });
        seaarchMedicine($('.medicine-search'))
    })(jQuery)
    function seaarchMedicine(inputBox){
        var __template = '<span>[name] - <span class="text-muted">[type]</span> <small class="ml-2 text-muted">[category]</small></span>'.replace(/\[+([^\][]+)]+/g,"<?= '{{$1}}' ?>");
        inputBox.typeahead({
            order: 'asc',
            source: {
                medicines: {
                    // cache: true,
                    display: ['name', 'category'],
                    template: __template,
                    ajax: {
                        url: "{{ route('api.medicines.search') }}"
                    }
                }
            },
            callback: {
                onClickAfter: function (node, a, item, event) { 
                    node.parents('tr').find('input.medicine-type').val(item.type)
                }
            }
        });
    }
</script>
@endpush