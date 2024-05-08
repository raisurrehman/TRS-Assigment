<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h3 class="card-title">Edit Products</h3>
            </div>
        </div>
    </div>
    <div class="card-content">
        <div class="card-body card-dashboard">
            <form method="post" id="edit-product-form" action="{{ route('products.update', ['id' => $product->id]) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="name">Name</label>
                    </div>
                    <div class="col-md-10 form-group">
                        <input type="text" name="name" value="{{ $product->name }}" placeholder="name..."
                            class="form-control">
                        <span class="text-danger" id="name"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="name">Categories</label>
                    </div>
                    <div class="col-md-10 form-group">
                        <select class="select2" multiple="multiple" data-placeholder="Select categories"
                            name="categories[]" style="width: 100%;">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->
                                categories->contains($category->id) ? 'selected' : '' }}>{{
                                $category->name }}</option>
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
                        <input type="number" name="price" value="{{ $product->price }}" placeholder="123"
                            class="form-control">
                        <span class="text-danger" id="price"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="email">Description</label>
                    </div>
                    <div class="col-md-10 form-group">
                        <textarea name="description" value="" placeholder="description..." class="form-control"
                            cols="30" rows="5">{{ $product->description }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="email">Images</label>
                    </div>
                    <div class="col-md-10 form-group border p-2">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="featured_image">Featured Image</label>
                                <div class="row">
                                    <div class="col-md-12 p-2">
                                        <img src="{{$product->featured_image_url}}" alt="Featured Image"
                                            class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                    <div class="col-md-12 p-2">
                                        <input type="file" name="featured_image" id="featured_image"
                                            class="form-control-file">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="gallery">Gallery Images</label>
                                <div class="gallery-images">
                                    @foreach ($product->getMedia('gallery') as $image)
                                    <img src="{{ $image->getUrl() }}" alt="Product Image" class="img-thumbnail"
                                        style="max-width: 100px; margin-right: 10px;">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="custom-file mt-3">
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label for="active">Active</label>
                    </div>
                    <div class="col-md-10">
                        <input type="checkbox" name="active" data-bootstrap-switch data-off-color="danger"
                            data-on-color="primary" data-size="small" data-on-text="Yes" data-off-text="No" value="1" {{
                            $product->active ?
                        'checked' : '' }}>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#edit-product-form').submit(function (e) {
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
                $('#edit-product').hide();

                toastr.success('Product updated successfully.');

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
                    }

                }
            }
        });
    });

    document.getElementById('featured_image').addEventListener('change', function (event) {
        var fileInput = event.target;
        var file = fileInput.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
            var imgElement = fileInput.parentElement.previousElementSibling.querySelector('img');
            imgElement.src = e.target.result;
        }

        reader.readAsDataURL(file);
    });


</script>