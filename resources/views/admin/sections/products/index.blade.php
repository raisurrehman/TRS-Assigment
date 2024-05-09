@extends('admin.layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div id="list-products">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="card-title">Products</h3>
                            </div>
                            <div class="col-auto">
                                <button id="create-product-btn" class="btn btn-primary">Create Product</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="products-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Categories</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Categories</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div id="create-product">

            </div>
            <div id="edit-product">
            </div>
        </div>
    </div>
</section>
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
                <p>Are you sure you want to delete this product?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Yes</button>
                <!-- Corrected class -->
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function () {

        $('#create-product-btn').click(function () {
            $.ajax({
                url: "{{ route('products.create') }}",
                type: 'GET',
                success: function (response) {
                    $('#list-products').hide();
                    $('#create-product').html(response).show();
                    $('.select2').select2();
                    $("input[data-bootstrap-switch]").each(function () {
                        $(this).bootstrapSwitch('state', $(this).prop('checked'));
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while loading the create page');
                }
            });
        });


        $(document).on('click', '.edit', function (e) {
            e.preventDefault();
            var productId = $(this).data('id');

            $.ajax({
                url: '/products/' + productId + '/edit',
                type: 'GET',
                success: function (response) {
                    $('#list-products').hide();
                    $('#edit-product').html(response).show();
                    $('.select2').select2();
                    $("input[data-bootstrap-switch]").each(function () {
                        $(this).bootstrapSwitch('state', $(this).prop('checked'));
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while loading the edit page');
                }
            });
        });

        var dataTable = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function (data, type, full, meta) {
                        var imageUrl = full.featured_image_url;
                        return '<div class="product-image-container">' +
                            '<img src="' + imageUrl + '" alt="' + data + '" class="product-image">' +
                            '<span class="product-name">' + data + '</span>' +
                            '</div>';
                    }
                },
                {
                    data: 'price',
                    name: 'price',
                    className: 'text-center align-middle'
                },
                {
                    data: 'categories',
                    name: 'categories',
                    className: 'align-middle',
                    render: function (data) {
                        var categoriesHtml = '';
                        data.forEach(function (category) {
                            categoriesHtml += '<span class="badge badge-primary">' + category.name + '</span> ';
                        });
                        return categoriesHtml;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'align-middle',
                    orderable: false,
                    searchable: false
                }

            ]
        });

        $(document).on('click', '.delete', function () {
            var productId = $(this).data('id');
            $('#confirmationModal').modal('show');

            $('#confirmDeleteBtn').off().one('click', function () {
                $.ajax({
                    url: '/products/' + productId,
                    type: 'DELETE',
                    success: function () {
                        toastr.success('Product deleted successfully.');
                        dataTable.ajax.reload();
                        $('#confirmationModal').modal('hide');
                    },
                    error: function (xhr, status, error) {
                        toastr.error('Error deleting product.');
                        $('#confirmationModal').modal('hide');
                    }
                });
            });
        });
        $('.toastrDefaultError').click(function () {
            toastr.error('This is an error toast message.');
        });
    });
</script>
@endpush
@endsection