@extends('front.layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('main')
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
                    <p>{{ Session::get('Registration Not Successful') }}</p>
                </div>
            @endif
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Register</h1>
                        <form action="" name="form" id="form" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="mb-2">Name*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter Name">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Password*</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password">
                                <p class="invalid-feedback"></p>
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                    placeholder="Enter Password">
                                <p class="invalid-feedback"></p>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Register</button>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Have an account? <a href="{{ route('account.login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $("#registrationForm").submit(function(event) {
                event.preventDefault();
                var element = $(this);
                $.ajax({
                    url: '{{ route('account.processRegistration') }}',
                    type: 'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == true) {  
                            window.location.href = "{{ route('account.login') }}";

                            $("#name").removeClass('is-invalid')
                                .siblings('.invalid-feedback').html("");

                            $("#email").removeClass('is-invalid')
                                .siblings('.invalid-feedback').html("");
                            $("#password").removeClass('is-invalid')
                                .siblings('.invalid-feedback').html("");

                            $("#confirm_password").removeClass('is-invalid')
                                .siblings('.invalid-feedback').html("");


                            // alert(response.message); 
                        } else {
                            var errors = response.errors;

                            if (errors.name) {
                                $("#name").addClass('is-invalid')
                                    .siblings('.invalid-feedback').html(errors.name);
                            } else {
                                $("#name").removeClass('is-invalid')
                                    .siblings('.invalid-feedback').html("");
                            }

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

                            if (errors.confirm_password) {
                                $("#confirm_password").addClass('is-invalid')
                                    .siblings('.invalid-feedback').html(errors.confirm_password);
                            } else {
                                $("#confirm_password").removeClass('is-invalid')
                                    .siblings('.invalid-feedback').html("");
                            }



                        }
                    }
                });
            });
        </script>


    </section>
@endsection

{{-- <script>
    document.getElementById('form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        const messageDiv = document.getElementById('message');
        
        try {
            const response = await fetch('http://localhost:8000/api/processRegistration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                window.location.href = "{{ route('account.login') }}";
                messageDiv.textContent = result.message;
                messageDiv.className = 'message success';

              
                $("#name").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                $("#email").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                $("#password").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                $("#confirm_password").removeClass('is-invalid').siblings('.invalid-feedback').html("");

               
            } else {
                messageDiv.textContent = result.message || 'An error occurred.';
                messageDiv.className = 'message error';

             
                const errors = result.errors || {};
                
                if (errors.name) {
                    $("#name").addClass('is-invalid').siblings('.invalid-feedback').html(errors.name);
                } else {
                    $("#name").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                }

                if (errors.email) {
                    $("#email").addClass('is-invalid').siblings('.invalid-feedback').html(errors.email);
                } else {
                    $("#email").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                }

                if (errors.password) {
                    $("#password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.password);
                } else {
                    $("#password").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                }

                if (errors.confirm_password) {
                    $("#confirm_password").addClass('is-invalid').siblings('.invalid-feedback').html(errors.confirm_password);
                } else {
                    $("#confirm_password").removeClass('is-invalid').siblings('.invalid-feedback').html("");
                }
            }
        } catch (error) {
            messageDiv.textContent = 'Request failed. Please try again.';
            messageDiv.className = 'message error';
        }

        messageDiv.style.display = 'block';
    });
</script> --}}
