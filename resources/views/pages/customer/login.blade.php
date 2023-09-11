@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <span>{{ __('Login') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Form Section Begin -->

<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="login-form">
                    <h2>{{ __('Login') }}</h2>
                    @if(Session::get('message'))
                    <div class="alert alert-danger" role="alert">
                        @php
                        echo Session::get('message');
                        Session::put('message', null);
                        @endphp
                    </div>
                    @endif
                    <form action="{{ URL::to('/login-customer-handle') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <label for="username">{{ __('Email address') }} *</label>
                            @php
                            $username = Session::get('username');
                            @endphp
                            @if ($username)
                            <input type="email" id="customer_email" name="customer_email" required
                                value="{{ $username }}">
                            @php
                            Session::put('username',null);
                            @endphp
                            @else
                            <input type="email" id="customer_email" name="customer_email" required
                                value="{{ old('customer_email') }}">
                            @endif

                        </div>
                        <div class="group-input">
                            <label for="pass">{{ __('Password') }} *</label>
                            <input type="password" id="customer_password" name="customer_password" required>
                        </div>
                        <div class="group-input gi-check">
                            <div class="gi-more">
                                <label for="save-pass">
                                    {{ __('Save Password') }}
                                    <input type="checkbox" id="save-pass">
                                    <span class="checkmark"></span>
                                </label>
                                <a href="{{ URL::to('/forget-password-customer') }}" class="forget-pass">{{ __('Forget your Password') }}</a>
                            </div>
                        </div>
                        {{-- <div class="group-input">
                            <a href="{{ URL::to('/login-customer-google') }}" class="btn btn-outline-secondary"><img
                                    src="{{ URL::asset('frontend/img/google.png') }}" width="50" alt=""> Google</a>
                            <a href="{{ URL::to('/login-customer-facebook') }}" class="btn btn-outline-secondary"><img
                                    src="{{ URL::asset('frontend/img/facebook.png') }}" width="50" alt=""> Facebook</a>
                        </div> --}}
                        <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_KEY')}}"></div>
                        <br />
                        @if($errors->has('g-recaptcha-response'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{$errors->first('g-recaptcha-response')}}</strong>
                        </span>
                        @endif
                        <button type="submit" class="site-btn login-btn">{{ __('Sign In') }}</button>
                    </form>
                    <div class="switch-login">
                        <a href="{{ URL::to('/register-customer') }}" class="or-login">{{ __('Or Create An Account') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form Section End -->
@push('add-script')
<script>
    $(function(){
            $('.nav-menu ul li').removeClass('active');
            $('.nav-menu ul li').eq(12).addClass('active');
        });
</script>
@endpush
@endsection