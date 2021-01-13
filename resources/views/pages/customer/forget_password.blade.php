@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a>
                    <span>Forget password</span>
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
                    <h2>Forget password</h2>
                    @if(Session::get('message'))
                    <div class="alert alert-danger" role="alert">
                        @php
                        echo Session::get('message');
                        Session::put('message', null);
                        @endphp
                    </div>
                    @endif
                    <form action="{{ URL::to('/mail-forget-password-customer') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <label for="username">Enter your email address *</label>

                            <input type="email" id="customer_email" name="customer_email" required>

                        </div>
                        <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_KEY')}}"></div>
                        <br />
                        @if($errors->has('g-recaptcha-response'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{$errors->first('g-recaptcha-response')}}</strong>
                        </span>
                        @endif
                        <button type="submit" class="site-btn login-btn">Get password</button>
                    </form>
                    <div class="switch-login">
                        <a href="{{ URL::to('/register-customer') }}" class="or-login">Or Create An Account</a>
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