@extends('admin.layouts.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">User Profile</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" id="profile-picture"
                                src="{{$user->profile?->avatar_url}}" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center" id="name">{{$user->name}}</h3>
                        <p class="text-muted text-center">{{$user->roles[0]->name ?? ''}}</p>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About Me</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> Education</strong>

                        <p class="text-muted" id="education">
                            {{$user->profile->education ?? ''}}
                        </p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                        <p class="text-muted" id="location">{{$user->profile->location ?? ''}}</p>

                        <hr>

                        <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                        <p class="text-muted" id="skills">
                            {{$user->profile->skills ?? ''}}
                        </p>

                        <hr>

                        <strong> <a href="{{$user->profile?->resume_url}}" target="__blank"><i
                                    class="far fa-file-alt mr-1"></i></a>Experience / Resume</strong>

                        <p class="text-muted" id="experience"> {{$user->profile->experience ?? ''}}</p>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item "><a class="nav-link active" href="#settings"
                                    data-toggle="tab">Settings</a>
                            </li>
                            <li class="nav-item "><a class="nav-link" href="#password" data-toggle="tab">Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" id="update-profile" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName" name="name"
                                                placeholder="Name" value="{{$user->name}}">
                                            <span class="text-danger" id="name-error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail" name="email"
                                                placeholder="Email" value="{{$user->email}}">
                                            <span class="text-danger" id="email-error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputAttachment" class="col-sm-2 col-form-label">Profile
                                            Image</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="avatar" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Education</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" name="education"
                                                placeholder="Education"> {{$user->profile->education ?? ''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" name="experience"
                                                placeholder="Experience"> {{$user->profile->experience ?? ''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills" name="skills"
                                                placeholder="Skills" value=" {{$user->profile->skills ?? ''}}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Location</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills" name="location"
                                                placeholder="location" value="{{$user->profile->location ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputAttachment" class="col-sm-2 col-form-label">Upload
                                            Resume</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="resume" class="custom-file-input"
                                                        id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="tab-pane" id="password">
                                <form class="form-horizontal" id="update-password">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-2 form-group">
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="password" name="password" placeholder="Password..."
                                                class="form-control">
                                            <span class="text-danger" id="password-error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                        </div>
                                        <div class="col-md-10 form-group">
                                            <input type="password" name="password_confirmation"
                                                placeholder="Confirm Password..." class="form-control">
                                            <span class="text-danger" id="password_confirmation-error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" id="updatePasswordBtn" class="btn btn-danger">Update
                                                Password</button>
                                        </div>
                                    </div>
                                </form>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $('#update-profile').on('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("profile.update") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                toastr.success('Profile updated successfully.');

                $('#name').text(response.user.name);
                $('#education').text(response.user.profile.education);
                $('#location').text(response.user.profile.location);
                $('#experience').text(response.user.profile.experience);
                $('#skills').text(response.user.profile.skills);

                var profilePicture = document.getElementById("profile-picture");
                profilePicture.src = response.user.profile.avatar_url;

                toastr.success('Profile updated successfully.');
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



    $('#update-password').on('submit', function (event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route("profile.update-password") }}',
            type: 'POST',
            data: formData,
            success: function (response) {
                toastr.success('password updated successfully.');
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
</script>
@endpush