@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a>
                    <span>Change password</span>
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
                    <h2>Change password</h2>
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
                    <form action="{{ URL::to('/update-new-password-handle') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <label for="username">Email address *</label>
                            <input type="email" id="customer_email" name="customer_email" required
                                value="{{ $_GET['email'] }}" readonly>
                            <input type="hidden" name="token" value="{{ $_GET['token'] }}">
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="customer_password" name="customer_password" required>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Confirm Password *</label>
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
                        <button type="submit" class="site-btn register-btn">Change password</button>
                    </form>
                    <div class="switch-login">
                        <a href="{{ URL::to('/login-customer') }}" class="or-login">Or Login</a>
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