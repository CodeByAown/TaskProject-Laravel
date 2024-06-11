@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Items</h1>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addItemModal">Add New Item</button>
    <a href="{{ route('items.exportt') }}" class="btn btn-success mb-3">Export Items</a>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><a href="{{ route('items.index', ['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">ID</a></th>
                <th><a href="{{ route('items.index', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Name</a></th>
                <th><a href="{{ route('items.index', ['sort' => 'brand', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Brand</a></th>
                <th><a href="{{ route('items.index', ['sort' => 'model', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Model</a></th>
                <th><a href="{{ route('items.index', ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Date Added</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->brand->name }}</td>
                    <td>{{ $item->productModel->name ?? 'N/A' }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>
                        <button type="button" class="btn btn-warning edit-btn" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-amount="{{ $item->amount }}" data-brand_id="{{ $item->brand_id }}" data-model_id="{{ $item->model_id }}">Edit</button>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $items->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
</div>


<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('items.store') }}" method="POST" id="addItemForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                        @if ($errors->has('amount'))
                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select class="form-control" id="brand_id" name="brand_id" required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand_id'))
                            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="model_id">Model</label>
                        <select class="form-control" id="model_id" name="model_id">
                            <!-- Models will be populated via JS based on the selected brand -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="editItemForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="edit_amount">Amount</label>
                        <input type="number" class="form-control" id="edit_amount" name="amount" required>
                        @if ($errors->has('amount'))
                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="edit_brand_id">Brand</label>
                        <select class="form-control" id="edit_brand_id" name="brand_id" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand_id'))
                            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="edit_model_id">Model</label>
                        <select class="form-control" id="edit_model_id" name="model_id">
                            <!-- Models will be populated via JS based on the selected brand -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
$(document).ready(function() {
    // Function to populate models dropdown based on selected brand
    function populateModelsDropdown(brandId, dropdownElement, selectedModelId, callback) {
        dropdownElement.empty();
        dropdownElement.append('<option value="">N/A</option>');
        if (brandId) {
            $.ajax({
                url: '/get-models-by-brand/' + brandId,
                type: 'GET',
                success: function(data) {
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + key + '">' + value + '</option>');
                        if (key == selectedModelId) {
                            option.prop('selected', true);
                        }
                        dropdownElement.append(option);
                    });
                    if (callback) callback();
                }
            });
        } else {
            if (callback) callback();
        }
    }

    // Show edit modal with item data
    $('.edit-btn').click(function() {
        var item_id = $(this).data('id');
        var name = $(this).data('name');
        var amount = $(this).data('amount');
        var brand_id = $(this).data('brand_id');
        var model_id = $(this).data('model_id');

        $('#editItemModal #edit_name').val(name);
        $('#editItemModal #edit_amount').val(amount);
        $('#editItemModal #edit_brand_id').val(brand_id);

        // Populate models dropdown in the edit modal based on the selected brand
        populateModelsDropdown(brand_id, $('#editItemModal #edit_model_id'), model_id, function() {
            $('#editItemForm').attr('action', '/items/' + item_id);
            $('#editItemModal').modal('show');
        });
    });

    // Populate models dropdown in the edit modal when brand changes
    $('#edit_brand_id').change(function() {
        var brandId = $(this).val();
        populateModelsDropdown(brandId, $('#edit_model_id'));
    });

    // Populate models dropdown in the add modal when brand changes
    $('#brand_id').change(function() {
        var brandId = $(this).val();
        populateModelsDropdown(brandId, $('#model_id'));
    });
});

</script>
@endpush

@endsection