@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

@endsection

@section("customJs")

{{-- <script>
    jQuery.validator.addMethod("customName", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z ]+$/);
    }, "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Only Characters and White Space are Allowed.</span>");

    jQuery.validator.addMethod("customEmail", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
    }, "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter valid email address!</span>");

    jQuery.validator.addMethod("customPassword", function(value, element) {
        return this.optional(element) || /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/i.test(value);
    }, "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> At least one number, one lowercase character(a-z), one uppercase character (A-Z), one special character needed!</span>");

    $("#registrationForm").validate({
        rules: {
            name: {
                minlength: 3,
                required: true,
                customName: true
            },
            email: {
                required: true,
                email: true,
                customEmail: true
            },
            // phone: {
            //     required: true,
            //     number: true,
            //     minlength: 11,
            //     maxlength: 11
            // },
            password: {
                required: true,
                minlength: 8,
                customPassword: true
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                customPassword: true,
                equalTo: "#password"
            }
        },
        messages: {
            name: {
                required: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter your name</span>",
                minlength: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Name at least 3 characters</span>"
            },
            email: {
                required: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter your email address</span>"
            },
            // phone: {
            //     required: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> please enter your phone number</span>",
            //     number: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter a valid phone number</span>",
            //     minlength: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter a 11 digit phone number</span>",
            //     maxlength: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter a 11 digit phone number</span>"
            // },
            password: {
                required: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please provide a password</span>",
                minlength: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Your password must be at least 8 characters</span>",
            },
            password_confirmation: {
                required: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please provide a confirm password</span>",
                minlength: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Your password must be at least 8 characters</span>",
                equalTo: "<span class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Please enter the same password as above</span>"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: "{{ route('register') }}",
                method: "POST",
                data: $("#registrationForm").serializeArray(),
                dataType: "json",
                success: function(data){
                    if(data.status == true){
                        window.location.href = "{{ route('login') }}";
                    }
                    // let info = JSON.parse(data);
                    // if(info.status == success){
                    //     alert("Signup Successfully");
                    //     alert("Confirm to verify your email");
                    //     $("#registrationForm").trigger("reset");
                    //     window.location.href = "{{ route('login') }}";
                    // }

                    // if(info.emailError){
                    //     $("#email_div_id").append("<label id='full-name-error' class='error' for='email-address'><i class='fa-solid fa-triangle-exclamation'></i> "+info.emailError.email_exist+"</label>");
                    // }

                    // if(info.contactError){
                    //     $("#phone_div_id").append("<label id='full-name-error' class='error' for='phone-number'><i class='fa-solid fa-triangle-exclamation'></i> "+info.contactError.contact_exist+"</label>");
                    // }

                    // console.log(info);
                },
                error: function(jQXHR, exception){
                    console.log("Something went wrong!");
                }
            });
            return false;
        }
    });
</script> --}}

@endsection