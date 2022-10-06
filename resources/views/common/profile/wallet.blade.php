@extends('layouts.app')
@section('title', 'Wallet')
{{-- total_earning total_withdraw pending_withdraw --}}
@section('content')
<header class="page-header">
    <h1 class="page-title">My Wallet</h1>
    <button class="btn btn-primary float-right h-100" type="button" data-toggle="modal" data-target="#withdraw">Withdraw</button>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-12 col-md-6">
            <div class="card animated fadeInUp delay-01s bg-light border-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Current Balance</h6>
                            <div class="count text-primary fs-20">{{ inCurrency(number_format($wallet->amount)) }}</div>
                        </div>
                        <div class="col col-5 text-right">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-wallet"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="card animated fadeInUp delay-02s bg-light border-secondary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Total Earning</h6>
                            <div class="count text-primary fs-20">{{ inCurrency(number_format($wallet->total_earning)) }}</div>
                        </div>
                        <div class="col col-5 text-right">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-money-bag"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="card animated fadeInUp delay-04s bg-light border-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Pending for Withdraw</h6>
                            <div class="count text-primary fs-20">{{ inCurrency(number_format($wallet->pending_withdraw)) }}</div>
                        </div>
                        <div class="col col-5 text-right">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-taka-minus"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="card animated fadeInUp delay-03s bg-light border-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Total Lifted</h6>
                            <div class="count text-primary fs-20">{{ inCurrency(number_format($wallet->total_withdraw)) }}</div>
                        </div>
                        <div class="col col-5 text-right">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-taka-true"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-0">
        <div class="card-header">Recent Withdrawals</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th scope="col" class="text-nowrap">#</th>
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
                        @forelse($withdrawals as $item)
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
                                @if( $item->isPending() )
                                <form class="actions" action="{{ route('admin.transactions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method("DELETE")
                                        <button type="submit" class="btn btn-error btn-sm btn-square rounded-pill">
                                            <span class="btn-icon icofont-ui-delete"></span>
                                        </button>
                                </form>
                                @endif
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
@endsection

@push('modal')
<div class="modal fade" id="withdraw" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form action="{{ url()->current() }}" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdraw Request</h5>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" name="modal-open" value="#withdraw">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Enter Amount</label>
                        <input type="number" class="form-control" name="amount" placeholder="Amount." min="100" max="{{ $wallet->amount }}" value="{{ old('amount') }}" required>
                        @error('amount')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label" for="method">Method</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" class="custom-control-input" value="mobile" name="method" id="method-mobile" checked>
                                <label class="custom-control-label" for="method-mobile">bKash/Rocket</label>
                            </div>
                            <div class="custom-control custom-radio mr-3">
                                <input type="radio" class="custom-control-input" value="bank" name="method" id="method-bank">
                                <label class="custom-control-label" for="method-bank">Bank Account</label>
                            </div>
                        </div>
                        @error('method')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="bkash">
                        <div class="form-group">
                            <label class="control-label">Enter Account Number</label>
                            <input type="text" class="form-control" name="account_number" placeholder="Bkash/Rocket Number" value="{{old('account_number')}}" autocomplete="off">
                            @error('account_number')
                            <span class="invalid-feedback" role="alert">{{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 d-none" id="bank">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Account Name</label>
                                <input type="text" class="form-control" name="bank_account_name" value="{{ old('bank_account_name') }}" placeholder="Account Name">
                                @error('bank_account_name')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Account Number</label>
                                <input type="text" class="form-control" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="Account Number">
                                @error('bank_account_number')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name') }}" placeholder="Bank Name">
                                @error('bank_name')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Branch Name</label>
                                <input type="text" class="form-control" name="bank_branch_name" value="{{ old('bank_branch_name') }}" placeholder="Branch Name">
                                @error('bank_branch_name')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Routing Number</label>
                                <input type="text" class="form-control" name="bank_routing_number" value="{{ old('bank_routing_number') }}" placeholder="Routing Number">
                                @error('bank_routing_number')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Comments</label>
                    <textarea placeholder="Message to Admin..." name="message" id="mes" cols="30" rows="2" class="form-control">{{ old('message') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="actions">
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        <form>
    </div>
</div>
@endpush

@push('footer')
<script type="text/javascript">
    (function($){
        $('input[name="method"]').on('change', function(){
            if( this.value === 'mobile' ){
                $('#bkash').removeClass('d-none');
                $('#bank').addClass('d-none');
            }else{
                $('#bkash').addClass('d-none');
                $('#bank').removeClass('d-none');
            }
        })
    })(jQuery);
</script>
@endpush