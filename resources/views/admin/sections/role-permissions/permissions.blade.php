@extends('admin.layouts.master')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Update Permissions</h1>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <form method="post" action="">
                                @csrf
                                <h4>{{ $role->name }} Permissions</h4>
                                <div class="row">
                                    @foreach($permissions as $permission)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="permission{{ $permission->id }}" {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('permissions')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
