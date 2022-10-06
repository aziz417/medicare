@extends('layouts.app')
@section('title', 'Appointments')
@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Find Doctor
                </div>
                <div class="card-body">
                    <form action="{{ route('user.doctors.index') }}" method="GET" class="row justify-content-center">
                        {{-- @csrf --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Search Term</label>
                                <input type="text" class="form-control" name="search" placeholder="Search...">
                                <span class="text-muted">Search by name, email, department name, specialist etc.</span>
                            </div>
                            <div class="form-group">
                                <label for="department" class="control-label">Select Department</label>
                                <select name="department" id="department" class="selectpicker" data-live-search="true">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="float-right btn btn-primary">Search Doctor</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer')
<script type="text/javascript">
    const DOCTORS = @json($doctors);
    (function($){
        $doctorList = $('#doctor');
        $('#department').on('change', function(){
            var $this = $(this),
                options = [];
                items = DOCTORS.filter(item=>Number(item.department_id)===Number($this.val()));
            if( $this.val() === "" ){
                items = DOCTORS;
                options.push('<option value="">Select Doctor</option>');
            }
            if( items.length > 0 ){
                items.forEach(item=>{
                    options.push(`<option data-subtext="${item.department.name}" value="${item.id}">${item.name}</option>`);
                });
            }else{
                showToaster("Doctors not found, Try again in another department.", {title: "Doctor Not Found Error!"})
                options.push(`<option value="">Doctors not Found</option>`);
            }
            $doctorList.html(options.join(''));

            if( $doctorList.parent('.bootstrap-select').length > 0 ){
                $doctorList.selectpicker('refresh')
            }else{
                $doctorList.selectpicker({
                    style: '',
                    styleBase: 'form-control',
                    tickIcon: 'icofont-check-alt'
                });
            }
        });
    })//(jQuery)
</script>
@endpush