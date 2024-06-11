@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Models</h1>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModelModal">Add Model</button>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
<!-- Adjust the table headers to include the Brand column -->
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Brand</th>
        <th>Items Count</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @foreach($models as $model)
        <tr>
            <td>{{ $model->id }}</td>
            <td>{{ $model->name }}</td>
            <td>{{ $model->brand->name }}</td> <!-- Display the brand name -->
            <td>{{ $model->items_count }}</td>
            <td>
                <!-- Edit and Delete buttons -->
                <button type="button" class="btn btn-warning edit-btn" data-id="{{ $model->id }}" data-name="{{ $model->name }}" data-brand_id="{{ $model->brand_id }}">Edit</button>
                <form action="{{ route('models.destroy', $model->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

    </table>
    {{ $models->links('pagination::bootstrap-4') }}
</div>

<!-- Add Model Modal -->
<div class="modal fade" id="addModelModal" tabindex="-1" role="dialog" aria-labelledby="addModelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('models.store') }}" method="POST" id="addModelForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addModelModalLabel">Add Model</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Brand</label>
                        <select class="form-control" id="brand_id" name="brand_id">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand_id'))
                            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Model</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Model Modal -->
<div class="modal fade" id="editModelModal" tabindex="-1" role="dialog" aria-labelledby="editModelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="editModelForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModelModalLabel">Edit Model</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="edit_brand_id">Brand</label>
                        <select class="form-control" id="edit_brand_id" name="brand_id">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand_id'))
                            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Model</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Show edit modal with model data
    $('.edit-btn').click(function() {
        var model_id = $(this).data('id');
        var name = $(this).data('name');
        var brand_id = $(this).data('brand_id');

        $('#editModelModal #edit_name').val(name);
        $('#editModelModal #edit_brand_id').val(brand_id);
        $('#editModelForm').attr('action', '/models/' + model_id);

        $('#editModelModal').modal('show');
    });
});
</script>
@endpush
@endsection
