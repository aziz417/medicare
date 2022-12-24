@extends('layouts.app')
@section('title', 'Doctors')

@section('content')
<header class="page-header">
    <h1 class="page-title">Doctors</h1>
    <form action="{{ url()->current() }}" class="float-right form-inline">
        <div class="form-group mr-2"><input type="text" class="form-control" name="search" placeholder="Search here..." value="{{ request('search') }}"></div>
        <div class="form-group mr-2">
            <select data-live-search="true" name="department" id="department" class="selectpicker">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                <option {{ request('department')==$department->id ? 'selected':'' }} value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        @if( count(array_filter(request()->all())) )
        <a href="{{ route('user.doctors.index') }}" class="ml-2 btn btn-sm btn-info">View All</a>
        @endif
    </form>
</header>
<div class="page-content">
    <div class="row mb-5">
        @forelse($doctors as $doctor)
        <div class="col-md-12">
            <div class="card doctor-card">
                <div class="card-body p-3">
                    <div class="row "> {{-- align-items-center --}}
                        <div class="col-md-2">
                            <div class="profile-img">
                                <img src="{{ asset($doctor->avatar()) }}" alt="{{ $doctor->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="d-flex align-items-center">
                                <a href="{{ route('user.doctors.show', $doctor->id) }}">{{ $doctor->name }}</a>
                                <div class="d-flex align-items-center flex-wrap-reverse w-50 ml-2">
                                    @foreach($doctor->badges as $badge)
                                    <span data-toggle="tooltip" title="{{ $badge->description }}" class="m-1 badge badge-sm badge-{{ $badge->color }}">{{ $badge->name }}</span>
                                    @endforeach
                                </div>
                            </h3>
                            <p><strong><i>{{ $doctor->getMeta('user_education_title') }}</i></strong></p>
                            <p>{{ $doctor->getMeta('user_designation') }}</p>
                            <p><a href="{{ route('user.doctors.index', ['department'=>$doctor->department_id]) }}" class="badge badge-sm border-primary">{{ $doctor->department->name ?? '' }}</a></p>
                            @if( $doctor->reviews->count() )
                            <div class="rating-container mt-2">
                                @php( $rating = (int) round($doctor->reviews->avg('rating'), 2) )
                                <select class="rating" data-readonly="true">
                                    <option {{ $rating==1 ? 'selected':'' }} value="1">1</option>
                                    <option {{ $rating==2 ? 'selected':'' }} value="2">2</option>
                                    <option {{ $rating==3 ? 'selected':'' }} value="3">3</option>
                                    <option {{ $rating==4 ? 'selected':'' }} value="4">4</option>
                                    <option {{ $rating>=5 ? 'selected':'' }} value="5">5</option>
                                  </select>
                            </div>
                            @endif
                            <p class="mt-2">
                                @foreach( explode(',', $doctor->getMeta('user_specialization', "")) as $item )
                                <span class="badge badge-sm badge-light">{{ $item }}</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="col-md-4">
                            <ul class="doctor-info">
                                <li><i class="icon icofont-comment"></i> Reviews: {{ sprintf('%02s', $doctor->reviews->count()) }}</li>
                                <li><i class="icon icofont-map-pins"></i> Address: {{ $doctor->getMeta('user_address', '~') }}</li>
                                <li><i class="icon icofont-bill-alt"></i> Charges: {{ inCurrency($doctor->getCharge('report')->amount) }} - {{ inCurrency($doctor->getCharge('booking')->amount) }}</li>
                            </ul>
                            <div class="actions mb-2">
                                <a href="{{ route('user.doctors.show', $doctor->id) }}" class="btn btn-block btn-outline-info">View Profile</a>
                                <a href="{{ route('user.doctors.booking', $doctor->id) }}" class="btn btn-block btn-primary">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="card doctor-card">
                <div class="card-body p-5">
                    @if( count(request()->all()) > 0 )
                    <h3 class="text-info">No doctor found with your search criteria!</h3>
                    <p>Try again with another term, or click <a href="{{ route('user.doctors.index') }}">here</a> to view all doctors.</p>
                    @else
                    <h3 class="text-danger">No Doctor Found!</h3>
                    @endif
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
