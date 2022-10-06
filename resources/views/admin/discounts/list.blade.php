@extends('layouts.app')
@section('title', 'Discounts')

@section('content')
<header class="page-header">
    <h1 class="page-title">Coupon Codes</h1>
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
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Amount/Percentage</th>
                                    <th scope="col">Expire at</th>
                                    <th scope="col">Available for</th>
                                    <th scope="col">Limit</th>
                                    <th scope="col">Used</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($discounts as $item)
                                <tr>
                                    <td>
                                        <div class="text-muted">{{ sprintf('%02s', $loop->iteration)  }}</div>
                                    </td>
                                    <td>{{ $item->code }}</td>
                                    @if(@$item->is_percentage == 0)
                                        <td>{{ inCurrency($item->amount) }}</td>
                                    @else
                                        <td>{{ $item->amount}}%</td>
                                    @endif
                                    <td>{{ optional($item->expire_at)->format('d M, Y') ?? 'Never' }}</td>
                                    <td>{{ optional($item->user)->name ?? 'All' }}</td>
                                    <td>{{ $item->limit }}</td>
                                    <td>{{ $item->used }}</td>
                                    <td>
                                        <div class="text-muted text-nowrap"><div class="badge badge-{{ $item->status=='active' ? 'success': 'warning' }}">{{ ucfirst($item->status ?? 'active') }}</div></div>
                                        </td>
                                    <td>
                                        <div class="btn-group">
                                            <form onsubmit="return confirm('Are you sure?')" action="{{ route('admin.discounts.destroy', $item->id) }}" method="POST">
                                                <button data-id="{{ $item->id }}" data-link="{{ route('admin.discounts.update', $item->id) }}" class="edit-item btn btn-primary btn-sm btn-square rounded-pill" type="button">
                                                    <span class="btn-icon icofont-ui-edit"></span>
                                                </button>
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit">
                                                    <span class="btn-icon icofont-trash"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">No Coupon Code Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="add-action-box">
        <button class="btn btn-primary btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-discount">
            <span class="btn-icon icofont-plus"></span>
        </button>
    </div>
</div>
@endsection


@push('modal')
<div class="modal fade" id="add-discount" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.discounts.store') }}" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Add discount</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="modal-open" value="#add-discount">
                    <div class="form-group">
                        <label>Coupon Code</label>
                        <input class="form-control" type="text" placeholder="Code" value="{{ old('code', strtoupper(str()->random(10))) }}" name="code" required>
                        @error('code')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="is_percentage" class="selectpicker" required>
                            <option value="1">Percentage</option>
                            <option value="0">Amount</option>
                        </select>
                        @error('is_percentage')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Discount Amount/Percentage</label>
                        <input class="form-control" type="number" placeholder="Amount" name="amount" required>
                        @error('amount')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Expire after</label>
                        <input class="form-control" type="date" placeholder="Date" name="expire_at">
                        @error('expire_at')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Usage Limit</label>
                        <input class="form-control" type="number" placeholder="Usage Limit" name="limit" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Available for</label>
                        <select name="available_for" class="selectpicker">
                            <option value="">All User</option>
                            @foreach($users as $item)
                            <option data-subtext="{{ ucfirst($item->role) }}" value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('available_for')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Add discount</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-discount" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="javascript:void(0)" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Discount</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal-open" value="#add-discount">
                    <div class="form-group">
                        <label>Coupon Code</label>
                        <input id="edit-code" class="form-control" type="text" placeholder="Code" value="{{ old('code') }}" name="code" readonly>
                        @error('code')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Discount Type</label>
                        <input id="edit-is_percentage" class="form-control" type="text" placeholder="Discount Type" value="{{ old('is_percentage') }}" name="is_percentage" readonly>
                        @error('is_percentage')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Discount Amount/Percentage</label>
                        <input id="edit-amount" class="form-control" type="number" placeholder="Amount" name="amount" readonly>
                        @error('amount')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Expire after</label>
                        <input id="edit-expire_at" class="form-control" type="date" placeholder="Date" name="expire_at">
                        @error('expire_at')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Usage Limit</label>
                        <input id="edit-limit" class="form-control" type="number" placeholder="Usage Limit" name="limit" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Available for</label>
                        <select id="edit-availabe_for" name="available_for" class="selectpicker">
                            @foreach($users as $item)
                            <option value="">All User</option>
                            <option data-subtext="{{ ucfirst($item->role) }}" value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('available_for')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update discount</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@push('footer')
<script type="text/javascript">
    var DISCOUNTS_LIST = @json($discounts->getCollection());
    (function($){
        $('.edit-item').on('click', function(){
            var link = $(this).data('link'), id = $(this).data('id'), item = DISCOUNTS_LIST.find(item=>item.id===id);
            console.log(item);
            if( link && item ){
                $('input#edit-code').val(item.code);
                if (item.is_percentage === 0) {
                    $('input#edit-is_percentage').val("Amount");
                }else {
                    $('input#edit-is_percentage').val("Percentage");
                }
                $('input#edit-amount').val(item.amount);
                $('input#edit-expire_at').val(item.expire_at);
                $('input#edit-limit').val(item.limit);
                $('input#edit-available_for').val(item.available_for);

                $('#edit-discount').find('form').attr('action', link);
                $('#edit-discount').modal('show');
            }
        })
    })(jQuery)
</script>
@endpush
