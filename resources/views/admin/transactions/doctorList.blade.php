@extends('layouts.app')
@section('title', 'Doctor Transactions')

@section('content')
<header class="page-header">
    <h1 class="page-title">Doctor Transactions</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col" class="text-nowrap">Bill NO</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Gateway</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $item)
                                <tr>
                                    <td>
                                        <div class="text-muted">#{{ sprintf('%03s', $item->id)  }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($item->user->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                            <strong class="ml-2">{{ $item->user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($item->gateway) }}</td>
                                    <td>
                                        {{ $item->description }}
                                    </td>
                                    <td>
                                        <div class="text-nowrap text-muted">{{ optional($item->created_at)->format('d M Y') }}</div>
                                    </td>
                                    <td>{{ inCurrency($item->final_amount) }}</td>
                                    <td><span title="Received Amount: {{ inCurrency($item->received_amount) }}" class="badge badge-{{statusClass($item->status)}}">{{ ucfirst($item->status) }}</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.transactions.show', $item->id) }}" class="btn btn-info btn-sm mr-1 btn-square rounded-pill">
                                                <span class="btn-icon icofont-eye"></span>
                                            </a>
                                            @if( $item->isPending() || $item->isWaiting() )
                                            <form class="actions" action="{{ route('admin.transactions.update', $item->id) }}" method="POST" autocomplete="off">
                                                @csrf @method('PUT')
                                                    <input type="hidden" name="received_amount" value="{{ $item->received_amount ?? $item->final_amount }}">
                                                    <button title="Quick Approve Transaction" data-toggle="tooltip" type="submit" name="action" value="approved" class="btn btn-success btn-sm mr-1 btn-square rounded-pill"><span class="btn-icon icofont-check"></span></button>
                                                    <button title="Quick Reject Transaction" data-toggle="tooltip" type="submit" name="action" value="rejected" class="btn btn-warning btn-sm mr-1 btn-square rounded-pill"><span class="btn-icon icofont-ban"></span></button>
                                            </form>
                                            @endif
                                            @if($auth->can('delete-transaction', $item) && $item->isCanceled())
                                            <form class="actions ml-1" action="{{ route('admin.transactions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method("DELETE")
                                                <button type="submit" class="btn btn-error btn-sm btn-square rounded-pill">
                                                    <span class="btn-icon icofont-ui-delete"></span>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
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
