@extends('front.layouts.app')

@section('title', 'Change Password - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('dashboard') }}">My Account</a>
                <span>/</span>
                <span class="current">Change Password</span>
            </nav>
        </div>
    </section>

    <!-- Change Password Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-4">
                    @include('front.message')
                </div>

                <div class="col-lg-3 mb-4 mb-lg-0">
                    @include('front.common.sidebar')
                </div>

                <div class="col-lg-9">
                    <div class="checkout-form-card">
                        <h3 class="checkout-title">
                            <i class="fas fa-lock"></i> Change Password
                        </h3>

                        <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="old_password" id="old_password" class="form-input" placeholder="Enter current password">
                                        <p class="text-danger small mt-1" id="old_password_error"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="new_password" id="new_password" class="form-input" placeholder="Enter new password">
                                        <p class="text-danger small mt-1" id="new_password_error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-input" placeholder="Confirm new password">
                                        <p class="text-danger small mt-1" id="confirm_password_error"></p>
                                    </div>
                                </div>
                            </div>

                            <button id="submit" name="submit" type="submit" class="btn-primary-premium">
                                <i class="fas fa-save me-2"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section("customJs")
<script>
    $("#changePasswordForm").submit(function(event){
        event.preventDefault();
        $("#submit").prop('disabled',true);

        $.ajax({
            url: "{{ route('users.processChangePassword') }}",
            type: 'POST',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                $("#submit").prop('disabled',false);

                // Clear previous errors
                $('.text-danger').html('');
                $('.form-input').removeClass('is-invalid');

                if(response.status == true){
                    window.location.href = "{{ route('front.change-password') }}";
                } else {
                    var errors = response.errors;

                    if(errors.old_password){
                        $("#old_password").addClass("is-invalid");
                        $("#old_password_error").html(errors.old_password);
                    }
                    if(errors.new_password){
                        $("#new_password").addClass("is-invalid");
                        $("#new_password_error").html(errors.new_password);
                    }
                    if(errors.confirm_password){
                        $("#confirm_password").addClass("is-invalid");
                        $("#confirm_password_error").html(errors.confirm_password);
                    }
                }
            }
        });
    });
</script>
@endsection