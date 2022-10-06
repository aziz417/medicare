@extends('layouts.app')
@section('title', 'View Patient')
@section('content')
<header class="page-header">
    <h1 class="page-title">{{ $patient->name }}'s Profile</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-12 col-md-6 mb-md-0">
            <div class="card bg-light {{-- personal-info-card --}}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 user-actions">
                        <img src="{{ asset($patient->avatar()) }}" width="100" height="100" alt="" class="rounded-500 mr-4">
                        <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-danger rounded-500">Edit Profile</a>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 mt-0 mr-1">{{ $patient->name }}</h5>
                    </div>
                    <p class="text-muted">
                        <h6 class="my-0">Age</h6>
                        <p>{{ $patient->getMeta('user_age', '~') }}</p>
                        <h6 class="my-0">Gender</h6>
                        <p>{{ $patient->getMeta('user_gender', '~') }}</p>
                        <h6 class="my-0">Blood Group</h6>
                        <p>{{ $patient->getMeta('user_blood_group', '~') }}</p>
                    </p>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="card mb-md-0">
                <div class="card-header">
                    Contact information
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col col-auto">
                            <div class="icon icofont-ui-touch-phone fs-30 text-muted"></div>
                        </div>
                        <div class="col">
                            <div>Mobile</div>
                            {{ $patient->mobile }}
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col col-auto">
                            <div class="icon icofont-email fs-30 text-muted"></div>
                        </div>
                        <div class="col">
                            <div>Email</div>
                            {{ $patient->email }}
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col col-auto">
                            <div class="icon icofont-location-pin fs-30 text-muted"></div>
                        </div>
                        <div class="col">
                            <div>Current Address</div>
                            {{ autop($patient->getMeta('user_address', '~')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0 mt-4">
                <div class="card-header">
                    Billings
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col" class="text-nowrap">Bill NO</th>
                                    <th scope="col">Gateway</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Tax</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->transactions as $item)
                                <tr>
                                    <td>
                                        <div class="text-muted">{{ sprintf('%03s', $item->id)  }}</div>
                                    </td>
                                    <td>{{ ucfirst($item->gateway) }}</td>
                                    <td>
                                        {{ $item->description }}
                                    </td>
                                    <td>
                                        <div class="text-nowrap text-muted">{{ $item->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>{{ inCurrency($item->amount) }}</td>
                                    <td>{{ inCurrency($item->tax) }}</td>
                                    <td>{{ inCurrency($item->discount) }}</td>
                                    <td>{{ inCurrency($item->final_amount) }}</td>
                                    <td><span title="Received Amount: {{ inCurrency($item->received_amount) }}" class="badge badge-{{statusClass($item->status)}}">{{ ucfirst($item->status) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">No Transaction Made Yet!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
