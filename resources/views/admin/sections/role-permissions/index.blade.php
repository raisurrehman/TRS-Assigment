@extends('admin.layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Roles & Permissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item active">Roles & Permissions</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<div class="modal fade" id="addRoleModal">
    <div class="modal-dialog">
        <form id="add-role-form" method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Role</h4>
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
                    <div id="permissions-container">
                        <!-- Permissions checkboxes will be appended here -->
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

<div class="modal fade" id="editRoleModal">
    <div class="modal-dialog">
        <form id="edit-role-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="role_id" id="edit-role-id">
                    <div class="form-group">
                        <label for="edit-name">Name</label>
                        <input id="edit-name" type="text" class="form-control" name="name" autofocus>
                        <span class="text-danger" id="name-error-edit"></span>
                    </div>
                    <div id="permissions-container-edit">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button> <!-- Corrected class -->
                </div>
            </div>
        </form>
    </div>
</div>

<section class="content">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="card-title">Users</h3>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary addNewRole">
                                    Add Role
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="roles-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>permissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>permissions</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var dataTable = $('#roles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles') }}",
            columns: [
                { name: 'id', data: 'id' },
                { name: 'name', data: 'name' },
                {
                    name: 'permissions',
                    data: 'permissions',
                    render: function (data) {
                        var badgesHtml = '';
                        if (Array.isArray(data)) {
                            badgesHtml = data.map(function (permission) {
                                return '<span class="badge badge-primary">' + permission.name + '</span>';
                            }).join(' ');
                        }
                        return badgesHtml;
                    }
                },
                { name: 'action', data: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('click', '.addNewRole', function () {
            $.ajax({
                url: "{{ route('get-permissions') }}",
                type: 'GET',
                success: function (response) {
                    console.log(response);
                    var permissionsContainer = $('#permissions-container');
                    permissionsContainer.empty();
                    response.permissions.forEach(function (permission) {
                        var checkbox = '<div class="form-check">' +
                            '<input type="checkbox" name="permissions[]" value="' + permission.id + '" class="form-check-input" id="permission' + permission.id + '">' +
                            '<label class="form-check-label" for="permission' + permission.id + '">' + permission.name + '</label>' +
                            '</div>';
                        permissionsContainer.append(checkbox);
                    });
                    $('#addRoleModal').modal('show');

                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Add Role Form Submission
        $('#add-role-form').on('submit', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route("roles.store") }}',
                type: 'POST',
                data: formData,
                success: function (response) {
                    toastr.success('Role created successfully.');
                    dataTable.ajax.reload();
                    $('#add-role-form')[0].reset();
                    $('#addRoleModal').modal('hide');
                },
                error: function (xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('#' + key + '-error').text(value[0]);
                        });
                    }
                }
            });
        });

        // Edit Role Button Click
        $(document).on('click', '.edit-role-btn', function () {
            var roleId = $(this).data('id');
            $.ajax({
                url: '/roles/' + roleId + '/edit',
                type: 'GET',
                success: function (response) {
                    $('#edit-role-id').val(roleId);
                    $('#edit-name').val(response.role.name);

                    $('#permissions-container-edit').empty();

                    response.permissions.forEach(function (permission) {
                        var checkbox = '<div class="form-check">' +
                            '<input type="checkbox" name="permissions[]" value="' + permission.id + '" class="form-check-input" id="editPermission' + permission.id + '"';

                        var hasPermission = response.role.permissions.find(function (rolePermission) {
                            return rolePermission.id === permission.id;
                        });

                        if (hasPermission) {
                            checkbox += ' checked';
                        }

                        checkbox += '>' +
                            '<label class="form-check-label" for="editPermission' + permission.id + '">' + permission.name + '</label>' +
                            '</div>';

                        $('#permissions-container-edit').append(checkbox);
                    });

                    $('#editRoleModal').modal('show');
                }
            });
        });


        // Update Role Form Submission
        $('#edit-role-form').on('submit', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var roleId = $('#edit-role-id').val();
            $.ajax({
                url: '/roles/' + roleId,
                type: 'PUT',
                data: formData,
                success: function (response) {
                    toastr.success('Role updated successfully.');
                    dataTable.ajax.reload();
                    $('#editRoleModal').modal('hide');
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
    });
</script>
@endpush