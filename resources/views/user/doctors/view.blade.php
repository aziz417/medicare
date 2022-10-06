@extends('layouts.app')
@section('title', 'View Doctors')

@push('header')
<style type="text/css">
    img.card-img-top {
        max-height: 120px;
        min-height: 100px;
        background-color: #eef;
    }
    .rounded-50p {
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">{{ $doctor->name }}'s Profile</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-md-12 mb-5">
            <div class="card doctor-card">
                <div class="card-body p-3">
                    <div class="row "> {{-- align-items-center --}}
                        <div class="col-md-2">
                            <div class="profile-img">
                                <img src="{{ asset($doctor->avatar()) }}" alt="{{ $doctor->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3><a href="{{ route('user.doctors.show', $doctor->id) }}">{{ $doctor->name }}</a></h3>
                            <p><strong><i>{{ $doctor->getMeta('user_education_title') }}</i></strong></p>
                            <p>{{ $doctor->getMeta('user_designation') }}</p>
                            <p><span class="badge badge-sm badge-primary">{{ $doctor->department->name ?? '' }}</span></p>
                            @if( $doctor->reviews->count() )
                            <div class="rating-container mt-2">
                                @php( $rating = (int) round($doctor->reviews->avg('rating')) )
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
                                <li><i class="icon icofont-email"></i> Email: {{ $doctor->email }}</li>
                                <li><i class="icon icofont-comment"></i> Reviews: {{ sprintf('%02s', $doctor->reviews->count()) }}</li>
                                <li><i class="icon icofont-map-pins"></i> Address: {{ $doctor->getMeta('user_address', '~') }}</li>
                                <li><i class="icon icofont-bill-alt"></i> Charges: {{ inCurrency($doctor->getCharge('report')->amount) }} - {{ inCurrency($doctor->getCharge('booking')->amount) }}</li>
                            </ul>
                            <div class="actions mb-2">
                                <a href="{{ route('user.doctors.booking', $doctor->id) }}" class="btn btn-block btn-primary">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-md-12 mb-2">
            <div class="card doctor-card p-4">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="p-3">
                            <h4>About</h4>
                            <div class="about">
                                {{ $doctor->getMeta('user_about') }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="p-3">
                            <h4>Top 10 Reviews</h4>
                            <div class="review-listing">
                                <ul id="reviews">
                                    @forelse($doctor->reviews->take(10) as $review)
                                    <li>
                                        <div class="comment">
                                            <img class="avatar" alt="User Image" src="{{ asset($review->patient->avatar()) }}">
                                            <div class="comment-body">
                                                <div class="meta-data">
                                                    <div class="info">
                                                        <span class="comment-author">{{ $review->patient->name }}</span>
                                                        <span class="comment-date">{{ $review->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="rating-container">
                                                        <select class="rating" data-readonly="true">
                                                            <option {{ $review->rating==1 ? 'selected':'' }} value="1">1</option>
                                                            <option {{ $review->rating==2 ? 'selected':'' }} value="2">2</option>
                                                            <option {{ $review->rating==3 ? 'selected':'' }} value="3">3</option>
                                                            <option {{ $review->rating==4 ? 'selected':'' }} value="4">4</option>
                                                            <option {{ $review->rating>=5 ? 'selected':'' }} value="5">5</option>
                                                          </select>
                                                    </div>
                                                </div>
                                                <p class="comment-content">
                                                    {{ $review->details }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    @empty
                                    <li>No Review Yet!</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div class="write-review">
                                <h4>Write a review for <strong>Dr. {{ $doctor->name }}</strong></h4>
                                <form id="add-review" action="{{ route('user.doctor.review', $doctor->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Review</label>
                                        <div class="star-rating">
                                            <select name="rating" class="rating">
                                                <option {{ old('rating')==1 ? 'selected':'' }} value="1">1</option>
                                                <option {{ old('rating')==2 ? 'selected':'' }} value="2">2</option>
                                                <option {{ old('rating')==3 ? 'selected':'' }} value="3">3</option>
                                                <option {{ old('rating')==4 ? 'selected':'' }} value="4">4</option>
                                                <option {{ old('rating')>=5 ? 'selected':'' }} value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Your review</label>
                                        <textarea name="details" id="review_desc" maxlength="100" class="form-control" placeholder="Write somethings...">{{ old('details') }}</textarea>
                                    </div>
                                    <hr>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn">Add Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection