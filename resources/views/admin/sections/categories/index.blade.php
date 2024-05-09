@extends('admin.layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Products</h3>
                        </div>
                        <div class="col-auto">
                            @can('create-categories')
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addCategoryModal">
                                Add Category
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="addCategoryModal">
                    <div class="modal-dialog">
                        <form id="add-category-form" method="POST" action="{{ route('categories.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Category</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input id="name" type="text" class="form-control" name="name" autofocus>
                                        <span class="text-danger" id="name-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" class="form-control" name="description"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button> <!-- Corrected class -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="editCategoryModal">
                    <div class="modal-dialog">
                        <form id="edit-category-form" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add Category</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="category_id" id="edit-category-id">
                                    <div class="form-group">
                                        <label for="edit-name">
                                            Name</label>
                                        <input id="edit-name" type="text" class="form-control" name="name" autofocus>
                                        <span class="text-danger" id="name-error-edit"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-description">Description</label>
                                        <textarea id="edit-description" class="form-control" name="description"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <!-- Corrected class -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="confirmationModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Confirmation</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this category?</p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Yes</button>
                                <!-- Corrected class -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="categories-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var dataTable = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,

            ajax: "{{ route('categories') }}",
            columns: [
                { name: 'id', data: 'id' },
                { name: 'name', data: 'name' },
                { name: 'description', data: 'description' },
                { name: 'action', data: 'action', orderable: false, searchable: false }
            ],
        });
        $('#add-category-form').on('submit', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route("categories.store") }}',
                type: 'POST',
                data: formData,
                success: function (response) {
                    toastr.success('Category created successfully.');
                    dataTable.ajax.reload();
                    $('#add-category-form')[0].reset(); // Clear form fields
                    $('#addCategoryModal').modal('hide');
                    $('.text-danger').text('');
                },
                error: function (xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('#' + key + '-error').text(value[0]); // Populate error message container
                        });
                    }
                }
            });
        });
        $(document).on('click', '.edit-category-btn', function () {
            var categoryId = $(this).data('id');
            $.ajax({
                url: '/categories/' + categoryId + '/edit',
                type: 'GET',
                success: function (response) {
                    $('#edit-category-form input[name="name"]').val(response.name);
                    $('#edit-category-form textarea[name="description"]').val(response.description);
                    $('#edit-category-id').val(categoryId);
                    $('#editCategoryModal').modal('show');
                }
            });
        });

        $('#edit-category-form').on('submit', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var categoryId = $('#edit-category-id').val();
            $.ajax({
                url: '/categories/' + categoryId,
                type: 'PUT',
                data: formData,
                success: function (response) {

                    toastr.success('Category updated successfully.');
                    dataTable.ajax.reload();
                    $('#editCategoryModal').modal('hide');
                    $('.text-danger').text('');

                },
                error: function (xhr, status, error) {

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('#' + key + '-error-edit').text(value[0]);
                        });
                    }
                }
            });
        });


        $(document).on('click', '.delete-category-btn', function () {
            var categoryId = $(this).attr('id').replace('delete-category-btn-', '');
            $('#confirmationModal').modal('show');

            $('#confirmDeleteBtn').data('category-id', categoryId);
        });

        $(document).on('click', '#confirmDeleteBtn', function () {
            var categoryId = $(this).data('category-id');

            $.ajax({
                url: '/categories/' + categoryId,
                type: 'DELETE',
                data: {},
                success: function (response) {
                    dataTable.ajax.reload();
                    toastr.success('Category deleted successfully.');
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.responseJSON.error || 'Error deleting category';
                    toastr.error(errorMessage);
                }
            });
            $('#confirmationModal').modal('hide');
        });

    });
</script>
@endpush