@extends('layouts.app')
@section('title', 'Schedules')

@push('header')
<style type="text/css">
    .slot-item {
        border-bottom: 1px solid #999;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">Schedules
    </h1>
    <form class="float-right d-flex" action="{{ url()->current() }}">
        <div class="form-group mr-1">
            <select name="doctor" class="selectpicker">
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                <option {{ request('doctor')==$doctor->id?'selected':'' }} value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mr-1">
            <select class="selectpicker" name="day" >
                <option value="">Select Day</option>
                @foreach(config('system.days') as $day)
                <option {{ request('day')==$day?'selected':'' }} value="{{ ucfirst($day) }}">{{ ucfirst($day) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group"><button class="btn btn-sm btn-primary">Filter</button></div>
    </form>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th scope="col">Doctor</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    <tr>
                                        <td class="d-flex">
                                            <img src="{{ asset($schedule->doctor->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                            <div class="ml-2">
                                                <strong>{{ $schedule->doctor->name ?? 'N/A' }}</strong> <br>
                                                <small>{{ $schedule->doctor->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                {{ _date($schedule->start_time, 'h:i A') }} - {{ _date($schedule->end_time, 'h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                {{ $schedule->duration }} Minutes
                                            </div>
                                        </td>
                                        <td>
                                            {{ ucfirst($schedule->day) }}
                                        </td>
                                        <td>
                                            <form title="ID: {{ $schedule->id }}" class="actions" action="{{ route('admin.schedules.destroy', $schedule->id) }}" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit"><span class="btn-icon icofont-trash"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="7">No Patient Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-action-box">
        <button class="btn btn-dark btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-schedule">
            <span class="btn-icon icofont-plus-circle"></span>
        </button>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="add-schedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <form class="modal-content needs-validation" novalidate action="{{ route('admin.schedules.store') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add schedule</h5>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" name="modal-open" value="#add-schedule">
                <div id="slot-list">
                    <div class="row border-bottom mb-3">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="">Doctor</label>
                                <select name="doctor_id" id="doctor" data-live-search="true" class="selectpicker">
                                    @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center slot-item">
                        <div class="col-md-10 col-12">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group selectable">
                                        <label>Day</label>
                                        <select class="selectpicker" name="day[]" >
                                            @foreach(config('system.days') as $day)
                                            <option value="{{ ucfirst($day) }}">{{ ucfirst($day) }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Duration</label>
                                        <input name="duration[]" class="form-control" type="number" step="15" min="15" max="60" placeholder="Minutes">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <input name="start_time[]" class="form-control" type="time" placeholder="Start Time">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <input name="end_time[]" class="form-control" type="time" placeholder="Start Time">
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-more">
                    <a id="clone-btn" href="javascript:void(0);" class="btn btn-outline-primary btn-square rounded-pill">
                      <span class="btn-icon icofont-plus"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="actions justify-content-between">
                    <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add schedule</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var TEMPLATE = '<div class="row mb-3 pb-3 pb-md-0 align-items-center slot-item slot-new-item"><div class="col-md-10 col-12"><div class="row"><div class="col-12 col-md-6"><div class="form-group selectable"> <label>Day</label> <select class="selectpicker" name="day[]"> @foreach(config('system.days') as $day)<option value="{{ ucfirst($day) }}">{{ ucfirst($day) }}</option> @endforeach </select></div></div><div class="col-12 col-md-6"><div class="form-group"> <label>Duration</label> <input name="duration[]" class="form-control" type="number" step="15" min="15" max="60" placeholder="Minutes"></div></div><div class="col-12 col-md-6"><div class="form-group"> <label>Start Time</label> <input name="start_time[]" class="form-control" type="time" placeholder="Start Time"></div></div><div class="col-12 col-md-6"><div class="form-group"> <label>End Time</label> <input name="end_time[]" class="form-control" type="time" placeholder="Start Time"></div></div></div></div><div class="col-md-2 col-12"><button type="button" class="btn btn-danger btn-square rounded-pill slot-remove-btn"><span class="btn-icon icofont-trash"></span></button></div></div>';
    (function($){
        $('#clone-btn').on('click', function(){
            $('#slot-list').append(TEMPLATE).find('.selectpicker').selectpicker({
                style: '',
                styleBase: 'form-control',
                tickIcon: 'icofont-check-alt'
            });
        });
        $(document).on('click', '.slot-remove-btn', function(){
            $(this).parents('.slot-item').remove();
        });
    })(jQuery)
</script>
@endpush
