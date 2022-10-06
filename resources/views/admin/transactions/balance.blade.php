@extends('layouts.app')
@section('title', 'Account Balance')

@section('content')
<header class="page-header">
    <h1 class="page-title">Account Balance</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-12 col-md-6 col-xl-3">
            <div class="card animated fadeInUp delay-04s bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-5">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-dollar-true"></div>
                        </div>
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1 text-nowrap">Total Earning</h6>
                            <div class="count text-primary fs-20">{{ inCurrency(sprintf('%02s', $accounts['earning'] ?? 0)) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection