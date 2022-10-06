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
                                <td>{{ ucfirst($transaction->gateway) }} ({{ ucfirst($transaction->method) }})</td>
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
                                        <a class="badge badge-sm badge-success" href="{{ route('admin.appointments.show', $transaction->appointment->id) }}">Appointment {{ $transaction->appointment->appointment_code }}</a>
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
                @isset($transaction->response['message'])
                    {{ $transaction->response['message'] }}
                @else
                    No Response Yet!
                @endisset
                </div>
                <div class="card-body">
                    @isset( $transaction->response['data'] )
                    <table class="table">
                        <tbody>
                            @foreach( $transaction->response['data'] ?? [] as $key => $value )
                            <tr>    
                                <td>{{ slug2title($key) }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endisset

                    @can('update-transaction', $transaction)
                    <hr>
                    @if( $transaction->isPending() || $transaction->isWaiting() )
                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" autocomplete="off">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label for="received_amount">Received Amount</label>
                            <input id="received_amount" class="form-control" type="number" name="received_amount" placeholder="Enter Received Amount" value="{{ $transaction->received_amount ?? $transaction->final_amount }}" required>
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <input id="comment" class="form-control" type="text" name="comment" placeholder="Write something..." value="{{ old('comment') }}">
                        </div>

                        <div class="btn-group btn-block mt-2">
                            <button type="submit" name="action" value="approved" class="btn btn-success">Approve</button>
                            <button type="submit" name="action" value="rejected" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection