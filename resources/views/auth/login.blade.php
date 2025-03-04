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
                        <form id="login-form">
                            @csrf
                            <div class="mb-2">
                                <label for="email" class="form-label">Email address</label>
                                <input class="form-control input-radius" type="email" id="email" name="email"  placeholder="Enter your email">
                                <span class="text-danger error-email"></span>
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control input-radius"  placeholder="Enter your password">
                                <span class="text-danger error-password"></span>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember"> Remember me </label>
                                </div>
                            </div>

                            <div class="d-grid mb-0 text-center">
                                <button class="btn btn-primary" id="loginSubmit" type="submit"> Log In </button>
                            </div>

                            <div class="alert alert-danger mt-3 d-none" id="login-error"></div>
                        </form>


                        <div class="text-center">
                            <h5 class="mt-3 text-muted">Sign in with</h5>
                            <ul class="social-list list-inline mt-3 mb-0">
                                <li class="list-inline-item">
                                    <a href="{{ route('auth.google') }}" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="text-center">
                    <p class="text-muted">Don't have an account? <a href="{{route('register')}}" class="text-custom">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("#login-form").on("submit", function(e) {
                e.preventDefault();

                // Get the button and store its original text
                let loginButton = $("#loginSubmit");
                let originalText = loginButton.html();

                // Change button text to loading spinner
                loginButton.html('<i class="fa fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

                // Clear previous errors
                $(".error-email, .error-password, #login-error").text("").addClass("d-none");

                $.ajax({
                    url: "{{ route('login.ajax') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            $.NotificationApp.send(
                                "Login Failed",
                                response.message || "Invalid credentials. Please try again.",
                                "top-right",
                                "rgba(0,0,0,0.2)",
                                "error"
                            );
                            loginButton.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.email) {
                                $(".error-email").text(errors.email[0]).removeClass("d-none");
                            }
                            if (errors.password) {
                                $(".error-password").text(errors.password[0]).removeClass("d-none");
                            }
                            if (errors.email && !errors.password) {
                                $("#login-error").text(errors.email[0]).removeClass("d-none");
                            }
                        }

                        // Show error notification
                        $.NotificationApp.send(
                            "Error",
                            "An unexpected error occurred. Please try again later.",
                            "top-right",
                            "rgba(0,0,0,0.2)",
                            "error"
                        );

                        loginButton.html(originalText).prop('disabled', false);
                    }
                });
            });            
        });
    </script>
@endsection
