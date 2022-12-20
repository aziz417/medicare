@extends('layouts.app')
@section('title', 'Edit Prescription')

@section('content')
<header class="page-header">
    <h2 class="page-title mt-0 mb-2">Edit Prescriptions</h2>
</header>
<hr class="my-2"/>
<div class="page-content mt-2">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('admin.prescriptions.update', $prescription->id) }}">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="mt-0">Basic Info</h4>
                                <div class="form-group">
                                    <label>Select Patient</label>
                                    <input type="text" class="form-control" value="{{ $prescription->patient->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Select Appointment</label>
                                    <input type="text" class="form-control" value="Appointment {{ $prescription->appointment->appointment_code }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Investigation Title</label>
                                    <input name="investigation_title" value="{{ $prescription->investigation_title ?? old('investigation_title') }}" id="title-update" placeholder="Title" type="text"
                                           class="form-control form-control-sm w-100" list="investigations-list"
                                           autocomplete="off" required>
                                    <datalist id="investigations-list">
                                        @foreach(config('system.investigations', []) as $iName)
                                            <option>{{ $iName }}</option>
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="advice">Advice</label>
                                    <textarea class="form-control" name="advice" id="advice" cols="30" rows="4" placeholder="Write Something...">{{ $prescription->advice ?? old('advice') }}</textarea>
                                    <br/>
                                    <select id="advices" name="advices" class="form-control" onchange="adviceSet()">
                                        <option selected>Select advices</option>
                                        @forelse($advices as $advice)
                                            <option>{{ $advice->title }}</option>
                                        @empty
                                            <optio>Not Found Any advice</optio>
                                        @endforelse
                                    </select>
                                </div>
                                <br/>
                                {{-- <div class="form-group">
                                    <label class="control-label" for="investigations">Investigations</label>
                                    <textarea class="form-control" name="investigations" id="investigations" cols="30" rows="4" placeholder="Write Something...">{{ $prescription->investigations ?? old('investigations') }}</textarea>
                                </div> --}}
                                <div class="form-group">
                                    <label class="control-label" for="status">Status</label>
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio mr-3">
                                            <input type="radio" class="custom-control-input" value="hide" name="status" id="status-hide" {{ $prescription->status=='hide' ? 'checked':'' }}>
                                            <label class="custom-control-label" for="status-hide">Hide</label>
                                        </div>
                                        <div class="custom-control custom-radio mr-3">
                                            <input type="radio" class="custom-control-input" value="active" name="status" id="status-active" {{ $prescription->status=='active' ? 'checked':'' }}>
                                            <label class="custom-control-label" for="status-active">Show</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h4 class="mt-0">Prescription Info
                            <div class="btn-group float-right">
                                <button id="show-history" type="button" class="btn btn-sm btn-info">Patient History</button>
                                <button id="show-investigations" type="button" class="btn btn-sm btn-primary">Patient Investigations</button>
                            </div>
                        </h4>
                        <div class="prescription-data">
                            <div class="form-group">
                                <label class="control-label" for="concern">Major Concern</label>
                                <textarea class="form-control" name="chief_complain" id="concern" cols="30" rows="1" placeholder="Write Something...">{{ $prescription->chief_complain ?? old('chief_complain') }}</textarea>
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
                                    @forelse($prescription->medicines as $medicine)
                                    <input type="hidden" name="medicine_ids[]" value="{{ $medicine->id }}">
                                    <tr>
                                        <td>
                                            <div class="typeahead__container cancel">
                                                <div class="typeahead__field">
                                                    <div class="typeahead__query">
                                                        <input class="form-control form-control-sm medicine-search" type="search" placeholder="Medicine Name" autocomplete="off" name="medicines[]"
                                                        value="{{$medicine->name}}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm medicine-type" type="text" name="type[]" value="{{$medicine->type}}" placeholder="Medicine Type" list="mtypes">
                                            <datalist id="mtypes">
                                                @foreach(config('system.medicine_types',[]) as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </datalist>
                                        </td>
                                        <td>
                                            <input class="form-control form-control-sm" type="text" name="quantity[]" value="{{$medicine->quantity}}" placeholder="Dosing" list="dosing" autocomplete="off">
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
                                            <input class="form-control form-control-sm" type="text" name="days[]" value="{{$medicine->days}}" placeholder="Days">
                                        </td>
                                        <td>
                                            <textarea name="instruction[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Instruction">{{ $medicine->instruction }}</textarea>
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
                                    @forelse($prescription->getNotes('diagnosis') as $item)
                                    <input type="hidden" name="diagnosis_ids[]" value="{{ $item->id }}">
                                    <tr>
                                        <td>
                                            <input class="form-control form-control-sm medicine-type" type="text" name="diagnosis_title[]" value="{{$item->title}}" placeholder="Diagnosis Name/Title">
                                        </td>
                                        <td>
                                            <textarea name="diagnosis_details[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Details...">{{ $item->details }}</textarea>
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

                        <h5>ICD 10</h5>
                        <div class="table-responsive" id="icd10">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Group Description</th>
                                    <th>ICD10 Code</th>
                                    <th>Who Full Description</th>
                                    {{--                                        <th>--}}
                                    {{--                                            <a id="diagnosis-clone-btn" href="javascript:void(0);"--}}
                                    {{--                                               class="btn btn-sm btn-outline-primary rounded-pill p-1"><span--}}
                                    {{--                                                        class="btn-icon icofont-plus"></span></a>--}}
                                    {{--                                        </th>--}}
                                </tr>
                                </thead>
                                <tbody id="icd10-items">
                                <tr>
                                    <td>
                                        <input class="form-control form-control-sm" type="text"
                                               name="group_desc" value=" {{ $prescription->group_desc ?? old('group_desc') }}" placeholder="Group Description" list="groupDesc"
                                               autocomplete="on">
                                        <datalist id="groupDesc">
                                            @forelse($icd10Advice as $item)
                                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                                            @empty

                                            @endforelse
                                        </datalist>
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="text"
                                               name="icd_code" value="{{ $prescription->icd_code ?? old('icd_code') }}" list="code" placeholder="ICD10 Code"
                                               autocomplete="on">
                                        <datalist id="code">
                                            @forelse($icd10Advice as $item)
                                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                                            @empty

                                            @endforelse
                                        </datalist>
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" type="text"
                                               name="who_full_desc" value="{{ $prescription->who_full_desc ?? old('who_full_desc') }}" placeholder="Who Full Description" list="whoFullDesc"
                                               autocomplete="on">
                                        <datalist id="whoFullDesc">
                                            @forelse($icd10Advice as $item)
                                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                                            @empty

                                            @endforelse
                                        </datalist>
                                    </td>
                                </tr>

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

@push('modal')
@includeIf('admin.prescriptions.extras.history', ['user_id' => $prescription->patient_id])
@includeIf('admin.prescriptions.extras.investigations', ['user_id' => $prescription->patient_id ?? null])
@endpush

@push('footer')
<script type="text/javascript">
    // advice set
    var oldAdvice = '';
    var flag = 0;

    function adviceSet() {
        var advice = "#"+$("#advices option:selected").text()+" ";
        if (flag === 0){
            flag === 1;
            oldAdvice = $("#advice").val();
        }

        oldAdvice += advice;
        $("#advice").html(oldAdvice);
    }

    const NEW_TEMPLATE = `<tr><td><div class="typeahead__container cancel"><div class="typeahead__field"><div class="typeahead__query"><input class="form-control form-control-sm medicine-search" type="search" placeholder="Medicine Name" autocomplete="off" name="medicines[]" /></div></div></div></td><td><input class="form-control form-control-sm medicine-type" type="text" name="type[]" list="mtypes" placeholder="Medicine Type"></td><td><input class="form-control form-control-sm" type="text" name="quantity[]" placeholder="Dosing" list="dosing" autocomplete="off"></td><td><input class="form-control form-control-sm" type="text" name="days[]" placeholder="Days"></td><td><textarea name="instruction[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Instruction"></textarea></td><td><button type="button" class="btn btn-danger btn-sm p-1 rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></td></tr>`;
    const NEW_DIAGNOSIS_TEMPLATE = `<tr><td><input class="form-control form-control-sm medicine-type" type="text" name="diagnosis_title[]" placeholder="Diagnosis Name/Title"></td><td><textarea name="diagnosis_details[]" cols="30" rows="1" class="form-control form-control-sm" placeholder="Details..."></textarea></td><td><button type="button" class="btn btn-danger btn-sm p-1 rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></td></tr>`;
    const APPOINTMENTS = @json($appointments);
    (function($){
        $('#show-history').on('click', function(){
            if( HISTORY_USER !=="" ){
                $('#patient-history').modal('show');
            }else{
                alert("Select a patient first!");
            }
        });
        $('#show-investigations').on('click', function(){
            if( HISTORY_USER !=="" ){
                $('#patient-investigations').modal('show');
            }else{
                alert("Select a patient first!");
            }
        });
        $('#clone-btn').on('click', function(){
            var newItem = $(NEW_TEMPLATE);
            seaarchMedicine(newItem.find('.medicine-search'))
            $('#medicine-items').append(newItem);
        });
        $('#diagnosis-clone-btn').on('click', function(){
            var newItem = $(NEW_DIAGNOSIS_TEMPLATE);
            $('#diagnosis-items').append(newItem);
        });
        $('#patient').on('change', function(){
            var user_id = this.value;
            var options = APPOINTMENTS.filter(item=>item.user_id===Number(user_id)).map(item=>{
                return `<option value="${item.id}">Appointment ${item.appointment_code}</option>`;
            });
            $('#appointment').html(options.join('')).selectpicker('refresh').selectpicker({
                style: '',
                styleBase: 'form-control',
                tickIcon: 'icofont-check-alt'
            });
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
