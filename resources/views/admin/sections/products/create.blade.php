<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h3 class="card-title">Edit Products</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form id="create-product-form" method="post" action="{{ route('products.store') }}"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="name">Name</label>
                </div>
                <div class="col-md-10 form-group">
                    <input type="text" name="name" value="{{old('name')}}" placeholder="name..." class="form-control">
                    <span class="text-danger" id="name"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="name">Categories</label>
                </div>
                <div class="col-md-10 form-group">
                    <select class="select2" multiple="multiple" data-placeholder="Select a State" name="categories[]"
                        style="width: 100%;">
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="categories"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="price">Price</label>
                </div>
                <div class="col-md-10 form-group">
                    <input type="number" name="price" value="{{old('price')}}" placeholder="123" class="form-control">
                    <span class="text-danger" id="price"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="email">Description</label>
                </div>
                <div class="col-md-10 form-group">
                    <textarea name="description" placeholder="description..." class="form-control" cols="30"
                        rows="5">{{old('description')}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="email">Images</label>
                </div>
                <div class="col-md-10 form-group">
                    <div class="custom-file">
                        <input type="file" name="images[]" class="form-control" multiple>
                        <span class="text-danger" id="images"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 form-group">
                    <label for="active">Active</label>
                </div>
                <div class="col-md-10">
                    <input type="checkbox" name="active" data-bootstrap-switch data-off-color="danger"
                        data-on-color="primary" data-size="small" data-on-text="Yes" data-off-text="No" value="1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group text-right">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#create-product-form').submit(function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#list-products').show();
                $('#create-product').hide();

                toastr.success('Product created successfully.');

                if ($.fn.DataTable.isDataTable('#products-table')) {
                    $('#products-table').DataTable().ajax.reload();
                } else {
                    $('#products-table').DataTable({
                    });
                }
            },
            error: function (xhr, status, error) {
                var errors = JSON.parse(xhr.responseText).errors;

                for (var fieldName in errors) {

                    var errorMessage = errors[fieldName][0];
                    if (fieldName === 'name') {
                        $('#name').text(errorMessage);
                    } else if (fieldName === 'price') {
                        $('#price').text(errorMessage);
                    } else if (fieldName === 'categories') {
                        $('#categories').text(errorMessage);
                    } else if (fieldName === 'images') {
                        $('#images').text(errorMessage);
                    }

                }
            }
        });
    });
</script>