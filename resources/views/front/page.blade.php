@extends('front.layouts.app')

@section('title', $page->name . ' - ASA Online Shop')

@section('content')

    <!-- Breadcrumb -->
    <section class="py-4" style="background: var(--color-gray-50);">
        <div class="container">
            <nav class="breadcrumb-premium">
                <a href="{{ route('front.home') }}">Home</a>
                <span>/</span>
                <span class="current">{{ $page->name }}</span>
            </nav>
        </div>
    </section>

    <!-- Page Content -->
    <section class="py-5">
        <div class="container">
            @if ($page->slug == 'contact-us')
                <div class="row">
                    <div class="col-12 mb-4">
                        <h1 class="section-title">{{ $page->name }}</h1>
                    </div>

                    @if(Session::has('success'))
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="checkout-form-card">
                            {!! $page->content !!}
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="checkout-form-card">
                            <h3 class="checkout-title">
                                <i class="fas fa-envelope"></i> Send us a Message
                            </h3>
                            <form method="post" id="contactForm" name="contactForm">
                                <div class="form-group mb-3">
                                    <label class="form-label">Your Name</label>
                                    <input class="form-input" id="name" type="text" name="name" placeholder="Enter your name">
                                    <p class="help-block with-errors text-danger small"></p>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input class="form-input" id="email" type="email" name="email" placeholder="Enter your email">
                                    <p class="help-block with-errors text-danger small"></p>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Subject</label>
                                    <input class="form-input" id="subject" type="text" name="subject" placeholder="Message subject">
                                    <p class="help-block with-errors text-danger small"></p>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-input" rows="4" id="message" name="message" placeholder="Write your message"></textarea>
                                    <p class="help-block with-errors text-danger small"></p>
                                </div>

                                <button class="btn-primary-premium w-100" id="form-submit-btn" type="submit">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="checkout-form-card">
                    <h1 class="section-title mb-4">{{ $page->name }}</h1>
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@section("customJs")
<script>
    $("#contactForm").submit(function(event){
        event.preventDefault();

        $("#form-submit-btn").prop('disabled',true);

        $.ajax({
            url: "{{ route('front.sendContactEmail') }}",
            type: 'POST',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#form-submit-btn").prop('disabled',true);
                    window.location.href = "{{ route('front.page',$page->slug) }}";
                } else {
                    var errors = response.errors;

                    if(errors.name){
                        $("#name").addClass("is-invalid");
                        $("#name").next("p").html(errors.name);
                    } else {
                        $("#name").removeClass("is-invalid");
                        $("#name").next("p").html("");
                    }

                    if(errors.email){
                        $("#email").addClass("is-invalid");
                        $("#email").next("p").html(errors.email);
                    } else {
                        $("#email").removeClass("is-invalid");
                        $("#email").next("p").html("");
                    }

                    if(errors.subject){
                        $("#subject").addClass("is-invalid");
                        $("#subject").next("p").html(errors.subject);
                    } else {
                        $("#subject").removeClass("is-invalid");
                        $("#subject").next("p").html("");
                    }
                }
            }
        });
    });
</script>
@endsection