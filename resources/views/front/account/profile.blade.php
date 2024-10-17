@extends('front.layouts.app')
@section('main')

<!-- jQuery (necessary for the JavaScript to work) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <body data-instant-intensity="mousedown">

        <section class="section-5 bg-2">
            <div class="container py-5">
                <div class="row">
                    <div class="col">
                        <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Account Settings</li>
                            </ol>
                        </nav>
                    </div>
                </div>


                @include('front.account.sidebar')
                <div class="col-lg-9">

                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session::get('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session::get('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow mb-4">
                        <form action="{{ route('account.profile.update', $user->id) }}" method="post" id="userForm"
                            name="userForm">
                            @csrf
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">My Profile</h3>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Name"
                                        class="form-control" value="{{ $user->name }}">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Email*</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Email"
                                        class="form-control" value="{{ $user->email }}">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Designation*</label>
                                    <input type="text" name="designation" id="designation" placeholder="Designation"
                                        class="form-control" value="{{ $user->designation }}">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Mobile*</label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Mobile"
                                        class="form-control" value="{{ $user->mobile }}">
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                    <div class="card border-0 shadow mb-4">
                        <form action="" method="POST" id="changePasswordForm" name="changePasswordForm">
                            @csrf
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">Change Password</h3>
                                <div class="mb-4">
                                    <label for="old_password" class="mb-2">Old Password*</label>
                                    <input type="password" name="old_password" id="old_password" placeholder="Old Password"
                                        class="form-control">
                                        <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="new_password" class="mb-2">New Password*</label>
                                    <input type="password" name="new_password" id="new_password" placeholder="New Password"
                                        class="form-control">
                                        <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="confirm_password" class="mb-2">Confirm Password*</label>
                                    <input type="password" name="confirm_password" id="confirm_password"
                                        placeholder="Confirm Password" class="form-control">
                                        <p></p> 
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <script>
            $("#changePasswordForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('account.updatePassword') }}',
                    type: 'post',
                    dataType: 'json',
                    data: $("#changePasswordForm").serializeArray(),
                    success: function(response) {

                        if (response.status == true) {
                            window.location.href = '{{ route('account.profile') }}';


                        } else {
                            var errors = response.errors;
                            if (errors.old_password) {
                                $("#old_password").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.old_password)
                            } else {
                                $("#old_password").removeClass('is-invalid')
                                    .siblings(p)
                                    .removeClass('invalid-feedback')
                                    .html('')
                            }

                            if (errors.new_password) {
                                $("#new_password").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.new_password)
                            } else {
                                $("#new_password").removeClass('is-invalid')
                                    .siblings(p)
                                    .removeClass('invalid-feedback')
                                    .html('')
                            }

                            if (errors.confirm_password) {
                                $("#confirm_password").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.confirm_password)
                            } else {
                                $("#confirm_password").removeClass('is-invalid')
                                    .siblings(p)
                                    .removeClass('invalid-feedback')
                                    .html('')
                            }
                        }

                    }

                });
            });
        </script>
    @endsection
