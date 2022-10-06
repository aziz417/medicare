@extends('layouts.app')
@section('title', 'Transactions')

@section('content')
<header class="page-header">
    <h1 class="page-title">Transactions</h1>
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
                                    <th scope="col">Gateway</th>
                                    <th scope="col">Type</th>
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
                                        <div class="text-muted">{{ $item->tnx_id  }}</div>
                                    </td>
                                    <td>{{ ucfirst($item->gateway) }}</td>
                                    <td>{{ ucfirst($item->type) }}</td>
                                    <td>
                                        {{ $item->description }}
                                    </td>
                                    <td>
                                        <div class="text-nowrap text-muted">{{ $item->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>{{ inCurrency($item->final_amount) }}</td>
                                    <td><span title="Received Amount: {{ inCurrency($item->received_amount) }}" class="badge badge-{{statusClass($item->status)}}">{{ ucfirst($item->status) }}</span></td>
                                    <td>
                                        <form class="actions" action="{{ route('user.transactions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method("DELETE")
                                            <a href="{{ route('user.transactions.show', $item->id) }}" class="btn btn-success btn-sm btn-square rounded-pill">
                                                <span class="btn-icon icofont-eye"></span>
                                            </a>
                                            @if( $item->isPending() )
                                                <button type="submit" class="btn btn-error btn-sm btn-square rounded-pill">
                                                    <span class="btn-icon icofont-ui-delete"></span>
                                                </button>
                                            @endif
                                        </form>
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