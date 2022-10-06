@extends('layouts.app')
@section('title', 'Medicines')

@section('content')
<header class="page-header">
    <h1 class="page-title">Medicines</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicines as $medicine)
                                    <tr class="medicine--{{$medicine->id}}">
                                        <td>
                                            <strong>{{ $medicine->name }}</strong>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                {{ $medicine->type }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                {{ $medicine->category ?? '~' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                {{ $medicine->quantity ?? '~' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                {{ $medicine->price ?? '~' }}
                                            </div>
                                        </td>
                                        <td>
                                            <a data-id="{{ $medicine->id }}" data-link="{{ route('admin.medicines.update', $medicine->id) }}" class="edit-medicine btn btn-info btn-sm btn-square rounded-pill"><span class="btn-icon icofont-ui-edit"></span></a>
                                            @if( $auth->isAdmin(false) )
                                            <button title="Delete Item" data-id="{{ $medicine->id }}" data-link="{{ route('admin.medicines.destroy', $medicine->id) }}" class="btn btn-danger btn-sm btn-square rounded-pill delete-medicine" type="button"><span class="btn-icon icofont-trash"></span></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="6">No Medicine Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $medicines->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-action-box">
        <button class="btn btn-dark btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-medicines">
            <span class="btn-icon icofont-plus"></span>
        </button>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="add-medicines" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <form class="modal-content" action="{{ route('admin.medicines.store') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add Slot <span id="mdo"></span> </h5>
            </div>
            <div class="modal-body">
                @csrf 
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <strong class="text-danger">{{ $error }}</strong><br>
                    @endforeach
                @endif
                <div id="item-list">
                    <div class="row align-items-center add-item">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input name="name[]" class="form-control" type="text" placeholder="Name">
                                    </div> 
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <input name="type[]" class="form-control" type="text" placeholder="Type" list="medicine-types">
                                        <datalist id="medicine-types">
                                            <option value="Tablet">
                                            <option value="Capsul">
                                            <option value="Syrup">
                                            <option value="Injection">
                                            <option value="Cream">
                                            <option value="Ointment">
                                        </datalist>
                                    </div> 
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <input name="category[]" class="form-control" type="text" placeholder="Category">
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-more mb-3">
                    <a id="clone-btn" href="javascript:void(0);" class="btn btn-outline-primary btn-square rounded-pill">
                      <span class="btn-icon icofont-plus"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <div class="actions">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit-medicine" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <form class="modal-content" action="{{ route('admin.medicines.store') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Edit Medicine </h5>
            </div>
            <div class="modal-body">
                @csrf @method("PUT")
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="form-group row">
                            <label class="col-4">Name</label>
                            <input class="col-8 form-control" type="text" name="name" id="edit-name" placeholder="Name">
                        </div>
                        <div class="form-group row">
                            <label class="col-4">Type</label>
                            <input class="col-8 form-control" type="text" name="type" id="edit-type" placeholder="Type" list="edit-medicine-types">
                            <datalist id="edit-medicine-types">
                                <option value="Tablet">
                                <option value="Capsul">
                                <option value="Syrup">
                                <option value="Injection">
                                <option value="Cream">
                                <option value="Oientment">
                            </datalist>
                        </div>
                        <div class="form-group row">
                            <label class="col-4">Category</label>
                            <input class="col-8 form-control" type="text" name="category" id="edit-category" placeholder="Category">
                        </div>
                        <div class="form-group row">
                            <label class="col-4">Quantity</label>
                            <input class="col-8 form-control" type="text" name="quantity" id="edit-quantity" placeholder="Quantity">
                        </div>
                        <div class="form-group row">
                            <label class="col-4">Price</label>
                            <input class="col-8 form-control" type="text" name="price" id="edit-price" placeholder="Price">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="actions">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush
@push('footer')
<script type="text/javascript">
    var MEDICINES_LIST = @json($medicines->getCollection());
    (function($){
        $('.edit-medicine').on('click', function(){
            var link = $(this).data('link'), 
                id = $(this).data('id'), 
                item = MEDICINES_LIST.find(item=>item.id===id);
            if( link && item ){
                $('input#edit-name').val(item.name);
                $('input#edit-type').val(item.type);
                $('input#edit-category').val(item.category);
                $('input#edit-price').val(item.price);
                $('input#edit-quantity').val(item.quantity);
                $('#edit-medicine').find('form').attr('action', link);
                $('#edit-medicine').modal('show');
            }
        })

        const TEMPLATE = `<div class="row align-items-center add-item"><div class="col-10"><div class="row"><div class="col-12 col-md-4"><div class="form-group"> <label>Name</label> <input name="name[]" class="form-control" type="text" placeholder="Name"></div></div><div class="col-12 col-md-4"><div class="form-group"> <label>Type</label> <input name="type[]" class="form-control" type="text" placeholder="Type" list="medicine-types"></div></div><div class="col-12 col-md-4"><div class="form-group"> <label>Category</label> <input name="category[]" class="form-control" type="text" placeholder="Category"></div></div></div></div><div class="col-2"> <button type="button" class="btn btn-danger btn-square rounded-pill item-remove-btn"><span class="btn-icon icofont-trash"></span></button></div></div>`;
        $('#clone-btn').on('click', function(){
            $('#item-list').append(TEMPLATE);
        });
        $(document).on('click', '.item-remove-btn', function(){
            $(this).parents('.add-item').remove();
        });
        $(document).on('click', '.delete-medicine', function(){
            var link = $(this).data('link'), id = $(this).data('id'), $this = $(this);
            console.log(link);
            if( link && confirm('Are you sure?') ){
                $.ajax({
                    url: link,
                    type: 'DELETE',
                }).then(response=>{
                    if( response?.status ){
                        $this.parents(`.medicine--${id}`).remove();
                    }
                });
            }
        });
    })(jQuery)
</script>
@endpush
