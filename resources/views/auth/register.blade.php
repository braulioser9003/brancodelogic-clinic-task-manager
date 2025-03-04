@extends('layouts.login')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card">

                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <a href="{{ url('/') }}" class="logo">
                                <img src="{{ asset('images/Logo_Company.png') }}" alt="" style="height: 61px;">
                            </a>
                            <p class="text-muted mb-4 mt-3">Enter your email and password to access admin panel.</p>
                        </div>
                        
                        <form id="register-form">
                            @csrf
                            <div class="mb-2">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input class="form-control" type="text" id="fullname" placeholder="Enter your name" name="name">
                                <span class="text-danger error-name"></span>
                            </div>
                            <div class="mb-2">
                                <label for="emailaddress" class="form-label">Email address</label>
                                <input class="form-control" type="email" id="emailaddress" placeholder="Enter your email" name="email">
                                <span class="text-danger error-email"></span>
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" placeholder="Enter your password" name="password">


                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <span class="text-danger error-password"></span>
                            </div>
                            <div class="mb-2">
                                <label for="confirm" class="form-label">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirm" class="form-control" placeholder="Enter your password" name="confirm">


                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <span class="text-danger error-confirm"></span>
                            </div>
                            <div class="d-grid text-center">
                                <button class="btn btn-primary" type="submit" id="registerSubmit"> Sign Up </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <h5 class="mt-3 text-muted">Sign in with</h5>
                            <ul class="social-list list-inline mt-3 mb-0">
                                <li class="list-inline-item">
                                    <a href="{{ route('auth.google') }}" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                </li>
                            </ul>
                        </div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Already have account? <a href="{{route('login')}}" class="text-custom">Sign In</a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("#register-form").on("submit", function(e) {
                e.preventDefault();

                let registerButton = $("#registerSubmit");
                let originalText = registerButton.html();

                // Change button to spinner
                registerButton.html('<i class="fa fa-spinner fa-spin"></i> Creating account...').prop('disabled', true);

                // Clear previous errors
                $(".error-name, .error-email, .error-password").text("").addClass("d-none");

                $.ajax({
                    url: "{{ route('register.ajax') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $.NotificationApp.send(
                                "Success",
                                "Account created successfully. Redirecting...",
                                "top-right",
                                "rgba(0, 200, 0, 0.5)",
                                "success"
                            );

                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                $(".error-name").text(errors.name[0]).removeClass("d-none");
                            }
                            if (errors.email) {
                                $(".error-email").text(errors.email[0]).removeClass("d-none");
                            }
                            if (errors.password) {
                                $(".error-password").text(errors.password[0]).removeClass("d-none");
                            }
                            if (errors.confirm) {
                                $(".error-confirm").text(errors.confirm[0]).removeClass("d-none");
                            }
                        } else {
                            $.NotificationApp.send(
                                "Error",
                                "Something went wrong. Please try again.",
                                "top-right",
                                "rgba(255,0,0,0.5)",
                                "error"
                            );
                        }
                    },
                    complete: function() {
                        registerButton.html(originalText).prop('disabled', false);
                    }
                });
            });
        });

    </script>
@endsection
