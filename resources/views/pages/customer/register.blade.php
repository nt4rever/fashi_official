@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <span>{{ __('Register') }}</span>
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
                <div class="register-form">
                    <h2>{{ __('Register') }}</h2>
                    @if(Session::get('message'))
                    <div class="alert alert-danger" role="alert">
                        @php
                        echo Session::get('message');
                        Session::put('message', null);
                        @endphp
                    </div>
                    @endif
                    @foreach ( $errors->all() as $message)
                    <p style="color: red">{{ $message }}</p>
                    @endforeach
                    <form action="{{ URL::to('/register-customer-handle') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <label for="username">{{ __('Your name') }}: *</label>
                            <input type="text" id="customer_name" name="customer_name" required
                                value="{{ old('customer_name') }}">
                        </div>
                        <div class="group-input">
                            <label for="username">{{ __('Email address') }} *</label>
                            <input type="email" id="customer_email" name="customer_email" required
                                value="{{ old('customer_email') }}">
                        </div>
                        <div class="group-input">
                            <label for="pass">{{ __('Password') }} *</label>
                            <input type="password" id="customer_password" name="customer_password" required>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">{{ __('Confirm Password') }} *</label>
                            <input type="password" id="customer_confirm_password" name="customer_confirm_password"
                                required>
                        </div>
                        <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_KEY')}}"></div>
                        <br />
                        @if($errors->has('g-recaptcha-response'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{$errors->first('g-recaptcha-response')}}</strong>
                        </span>
                        @endif
                        <button type="submit" class="site-btn register-btn">{{ __('Register') }}</button>
                    </form>
                    <div class="switch-login">
                        <a href="{{ URL::to('/login-customer') }}" class="or-login">{{ __('Or Login') }}</a>
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
            $('.nav-menu ul li').eq(11).addClass('active');
        });
</script>
@endpush
@endsection