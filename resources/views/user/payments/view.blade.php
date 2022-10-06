@extends('layouts.app')
@section('title', 'View Transaction')

@section('content')
<header class="page-header d-flex justify-content-between">
    <h1 class="page-title">Transaction {{ $transaction->tnx_id }}</h1>
    <div class="transaction-info">
        <div class="badge rounded badge-{{ statusClass($transaction->status) }}">Status: &nbsp;&nbsp;<strong>{{ ucfirst($transaction->status) }}</strong></div>
    </div>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-light">
                <div class="card-body">
                    <table class="table list-table">
                        <tbody>
                            <tr>
                                <td>Transaction</td>
                                <td>:</td>
                                <td>{{ $transaction->tnx_id }}</td>
                            </tr>
                            <tr>
                                <td>Gateway</td>
                                <td>:</td>
                                <td>{{ ucfirst($transaction->gateway) }}</td>
                            </tr>
                            <tr>
                                <td>User</td>
                                <td>:</td>
                                <td>{{ $transaction->user->name }} - {{ $transaction->user->email }}</td>
                            </tr>
                            <tr>
                                <td>Transaction Date</td>
                                <td>:</td>
                                <td>{{ $transaction->created_at->format('d M Y - h:iA') }}</td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <td>:</td>
                                <td>{{ inCurrency($transaction->amount) }}</td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td>:</td>
                                <td title="{{$transaction->discount_code}}">{{ $transaction->discount_code ? inCurrency($transaction->discount) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Tax</td>
                                <td>:</td>
                                <td>{{ $transaction->tax }}</td>
                            </tr>
                            <tr>
                                <td>Total Amount</td>
                                <td>:</td>
                                <td>{{ $transaction->final_amount }}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <strong class="mr-3">{{ ucfirst($transaction->type) }}</strong>
                                        @if( $transaction->type == 'appointment' && $transaction->appointment )
                                        <a class="badge badge-sm badge-success" href="{{ route('user.appointments.show', $transaction->appointment->id) }}">Appointment {{ $transaction->appointment->appointment_code }}</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td>:</td>
                                <td>{{ $transaction->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header">
                @isset($transaction->approved_by['admin-comment'])
                    {{ $transaction->approved_by['admin-comment'] }}
                @else
                    Not approved yet!
                @endisset
                </div>
                <div class="card-body">
                    @if( $transaction->approved_by )
                    <p>Approver Name:<strong> {{ $transaction->approved_by['user_name'] ?? 'Admin' }}</strong></p>
                    @endif
                    {{-- @isset( $transaction->response['data'] )
                    <table class="table">
                        <tbody>
                            @foreach( $transaction->response['data'] ?? [] as $key => $value )
                            @if( is_string($value) )
                            <tr>    
                                <td>{{ slug2title($key) }}</td>
                                <td>{{ is_string($value) ? $value : null }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    @endisset --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection