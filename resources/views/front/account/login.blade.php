@extends('front.layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('main')

    <body data-instant-intensity="mousedown">

        <section class="section-5">
            <div class="container my-5">
                <div class="py-lg-2">&nbsp;</div>

                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">
                        <p>{{ Session::get('error') }}</p>
                    </div>
                @endif

                <div class="row d-flex justify-content-center">
                    <div class="col-md-5">
                        <div class="card shadow border-0 p-5">
                            <h1 class="h3">Login</h1>
                            <form id='loginForm' name="loginForm" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="" class="mb-2">Email*</label>
                                    <input type="text" value="{{ old('email') }}" name="email" id="email"
                                        class="form-control" placeholder="example@example.com">
                                    <p class="invalid-feedback"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="mb-2">Password*</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter Password">
                                    <p class="invalid-feedback"> </p>
                                </div>
                                <div class="justify-content-between d-flex">
                                    <button class="btn btn-primary mt-2">Login</button>
                                    <a href="{{ route('account.forgotPassword') }}" class="mt-3">Forgot Password?</a>
                                </div>
                            </form>
                        </div>
                        <div class="mt-4 text-center">
                            <p>Do not have an account? <a href="{{ route('account.registration') }}">Register</a></p>
                        </div>
                    </div>
                </div>
                <div class="py-lg-5">&nbsp;</div>
            </div>


            <script>
                    $("#loginForm").submit(function(event) {
                        event.preventDefault();
                        var element = $(this);
                        $.ajax({
                            url: '{{ route('account.auth') }}',
                            type: 'post',
                            data: element.serializeArray(),
                            dataType: 'json',
                            success: function(response) {
                                if (response.status == true) {
                                    window.location.href = "{{ route('account.profile') }}";

                                } else {
                                    var errors = response.errors;
                                    if (errors.email) {
                                        $("#email").addClass('is-invalid')
                                            .siblings('.invalid-feedback').html(errors.email);
                                    } else {
                                        $("#email").removeClass('is-invalid')
                                            .siblings('.invalid-feedback').html("");
                                    }

                                    if (errors.password) {
                                        $("#password").addClass('is-invalid')
                                            .siblings('.invalid-feedback').html(errors.password);
                                    } else {
                                        $("#password").removeClass('is-invalid')
                                            .siblings('.invalid-feedback').html("");
                                    }

                                }
                            }
                        });
                    });
                
            </script>
        </section>
    @endsection
