@extends('layouts.app')
@section('title', 'Schedules')

@section('content')
<header class="page-header">
    <h1 class="page-title">Schedules</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card schedule-widget mb-0">
                <div class="schedule-header">
                    <div class="schedule-nav">
                        <ul class="nav nav-tabs nav-justified">
                            @foreach(config('system.days', []) as $day)
                            <li class="nav-item">
                                <a class="nav-link {{ isDay(ucfirst($day), 'active') }}" data-toggle="tab" href="#slot_{{ strtolower($day) }}">{{ ucfirst($day) }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="tab-content schedule-cont">
                    @foreach(config('system.days', []) as $day)
                        <div id="slot_{{ strtolower($day) }}" class="tab-pane fade {{ isDay(ucfirst($day), 'active show') }}">
                            <h4 class="card-title d-flex justify-content-between">
                                <span>Time Slots</span>
                                <a class="btn btn-sm btn-primary" data-toggle="modal" href="#add-time-slot" data-whatever="{{ ucfirst($day) }}"> Add Slot</a>
                            </h4>
                            @if( $schedules->has(ucfirst($day)) )
                            <div class="doc-times">
                                @foreach($schedules->get(ucfirst($day)) as $item)
                                <div class="doc-slot-list slot--{{ $item->id }}">
                                    {{ _date($item->start_time, 'h:i A') }} - {{ _date($item->end_time, 'h:i A') }}
                                    <a href="javascript:void(0)" title="Delete Item" data-id="{{ $item->id }}" data-link="{{ route('admin.schedules.destroy', $item->id) }}" class="delete-schedule">
                                        <i class="icon icofont-close-line"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted mb-0">Not Available</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="ml-4">
                <h3>Schedule On/Off</h3>
                @php
                    use App\Models\DoctorScheduleOnOff;$preSchedule = DoctorScheduleOnOff::where('doctor_id', auth()->id())->first();
                @endphp
                <form class="form" method="post" action="{{ route('admin.doctor.schedule.onoff') }}">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-2">
                                <label for="on_off_true">Schedule</label>
                                <input name="on_off" {{ $preSchedule->on_off == 1 ? 'checked' : '' }} type="checkbox" class="" value="true" id="on_off_true">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('footer')
<div class="modal fade" id="add-time-slot" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <form class="modal-content" action="{{ route('admin.schedules.store') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add Slot <span id="mdo"></span> </h5>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" id="day" name="day">
                <div id="slot-list">
                    <div class="row align-items-center slot-item">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <input name="start_time[]" class="form-control" type="time" placeholder="Start Time">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <input name="end_time[]" class="form-control" type="time" placeholder="Start Time">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Duration</label>
                                        <input name="duration[]" class="form-control" type="number" step="15" min="15" max="60" placeholder="Minutes">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-more mb-3">
                    <a id="clone-btn" href="javascript:void(0);" class="btn btn-outline-primary btn-square rounded-pill">
                      <span class="btn-icon icofont-plus"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <div class="actions">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    (function($){
        const TEMPLATE = `<div class="row align-items-center slot-item"><div class="col-10"><div class="row"><div class="col-12 col-md-4"><div class="form-group"> <label>Start Time</label> <input name="start_time[]" class="form-control" type="time" placeholder="Start Time"></div></div><div class="col-12 col-md-4"><div class="form-group"> <label>End Time</label> <input name="end_time[]" class="form-control" type="time" placeholder="Start Time"></div></div><div class="col-12 col-md-4"><div class="form-group"> <label>Duration</label> <input name="duration[]" class="form-control" type="number" step="15" min="15" max="60" placeholder="Minutes"></div></div></div></div><div class="col-2"> <button type="button" class="btn btn-danger btn-square rounded-pill slot-remove-btn"><span class="btn-icon icofont-trash"></span></button></div></div>`;
        $('#clone-btn').on('click', function(){
            $('#slot-list').append(TEMPLATE);
        });
        $(document).on('click', '.slot-remove-btn', function(){
            $(this).parents('.slot-item').remove();
        });
        $(document).on('click', '.delete-schedule', function(){
            var link = $(this).data('link'), id = $(this).data('id'), $this = $(this);
            if( link && confirm('Are you sure?') ){
                $.ajax({
                    url: link,
                    type: 'DELETE',
                }).then(response=>{
                    if( response?.status ){
                        $this.parents(`.slot--${id}`).remove();
                    }
                });
            }
        });
        $('#add-time-slot').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var day = button.data('whatever')
            var modal = $(this);
            modal.find('#mdo').text(` - ${day}`);
            modal.find('input#day').val(day);
        })
    })(jQuery)
</script>

@endpush
