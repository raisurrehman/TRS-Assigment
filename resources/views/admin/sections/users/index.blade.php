@extends('admin.layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="modal fade" id="addNewUserModal">
    <div class="modal-dialog">
        <form id="add-user-form" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="name">Name</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name..."
                                class="form-control">
                            <span class="text-danger" id="name-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email..."
                                class="form-control">
                            <span class="text-danger" id="email-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="password">Password</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <input type="password" name="password" placeholder="Password..." class="form-control">
                            <span class="text-danger" id="password-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="role">Role</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <select class="form-control select2 select2-hidden-accessible" name="role"
                                style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            </select>
                            <span class="text-danger" id="roles-error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <!-- Corrected class -->
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="editUserModal">
    <div class="modal-dialog">
        <form id="edit-user-form" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="name">Name</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name..."
                                class="form-control">
                            <input type="hidden" name="user_id" id="edit-user-id">
                            <span class="text-danger" id="name-error-edit"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email..."
                                class="form-control">
                            <span class="text-danger" id="email-error-edit"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="password">Password</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <input type="password" name="password" placeholder="Password..." class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="role">Role</label>
                        </div>
                        <div class="col-md-10 form-group">
                            <select class="form-control select2 select2-hidden-accessible" name="role"
                                style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                            </select>

                            <span class="text-danger" id="roles-error-edit"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <!-- Corrected class -->
                </div>
            </div>
        </form>
    </div>
</div>
<section class="content">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card" id="list-users-page">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="card-title">Users</h3>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary addNewUser">Add Users</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="user-table" class="table table-bordered table-striped">
                            <thead>
                                <th>#</th>
                                <th>Name</th>
                                <th>email</th>
                                <th>Action</th>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>email</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="card" id="assign-role-page">
                </div>
            </div>
        </div>
    </section>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var dataTable = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users') }}",
            columns: [
                { name: 'id', data: 'id' },
                { name: 'name', data: 'name' },
                { email: 'email', data: 'email' },
                { name: 'action', data: 'action', orderable: false, searchable: false }
            ],
        });

        $(document).on('click', '.addNewUser', function (e) {
            $.ajax({
                url: "{{ route('get-roles') }}",
                type: 'GET',
                success: function (response) {
                    var roles = response.roles;
                    var selectRoles = $('#add-user-form select[name="role"]');
                    selectRoles.empty();

                    $.each(roles, function (index, role) {
                        selectRoles.append($('<option>', {
                            value: role.id,
                            text: role.name
                        }));
                    });
                    selectRoles.select2();
                    $('#addNewUserModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).on('click', '.edit-user-btn', function () {
            var userId = $(this).data('id');
            $.ajax({
                url: '/users/' + userId + '/edit',
                type: 'GET',
                success: function (response) {

                    $('#editUserModal input[name="name"]').val(response.user.name);
                    $('#editUserModal input[name="email"]').val(response.user.email);
                    $('#editUserModal input[name="password"]').val('');

                    $('#edit-user-id').val(userId);

                    var roles = response.roles;
                    $('#editUserModal select[name="role"]').empty();
                    $.each(roles, function (index, role) {
                        var option = $('<option>', {
                            value: role.id,
                            text: role.name
                        });

                        if (response.user.roles.some(r => r.id === role.id)) {
                            option.attr('selected', 'selected');
                        }

                        $('#editUserModal select[name="role"]').append(option);
                    });

                    $('#editUserModal select[name="role"]').select2();
                    $('#editUserModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('#add-user-form').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                success: function (response) {

                    toastr.success('User created successfully.');
                    $('#addNewUserModal').modal('hide');
                    $('#add-user-form')[0].reset();
                    dataTable.ajax.reload();
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

        $('#edit-user-form').on('submit', function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var userId = $('#edit-user-id').val();
            $.ajax({
                url: '/users/' + userId + '/update',
                type: 'PUT',
                data: formData,
                success: function (response) {
                    toastr.success('User updated successfully.');
                    $('#editUserModal').modal('hide');
                    dataTable.ajax.reload();
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