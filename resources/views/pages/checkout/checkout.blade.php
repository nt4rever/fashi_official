@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text product-more">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a>
                    <span>{{ __('Check Out') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<form action="">
    @csrf
</form>
<!-- Shopping Cart Section Begin -->
<section class="checkout-section spad">
    <div class="container">
        <form action="{{ URL::to('/checkout-place-order') }}" class="checkout-form" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="checkout-content">
                        <p class="content-btn">{{ __('Please fill in the form below') }}</p>
                    </div>
                    <h4>{{ __('Biiling Details') }}</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="fir">{{ __('First Name') }}<span>*</span></label>
                            <input type="text" id="fir" name="fir" required value="{{ old('fir') }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="last">{{ __('Last Name') }}<span>*</span></label>
                            <input type="text" id="last" name="last" required value="{{ old('last') }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="thanhpho">Tỉnh, thành phố<span>*</span></label>
                            <select name="thanhpho" id="thanhpho" class="form-control" style="margin-bottom: 25px">
                                <option value="" disabled selected>Chọn tỉnh - thành phố</option>
                                @foreach ($thanhpho as $item)
                                <option value="{{ $item->name }}" data-id="{{ $item->code }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="quanhuyen">Quận huyện<span>*</span></label>
                            <select name="quanhuyen" id="quanhuyen" class="form-control" style="margin-bottom: 25px">
                                <option value="" disabled selected>Chọn quận - huyện</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label for="xaphuong">Xã phường<span>*</span></label>
                            <select name="xaphuong" id="xaphuong" class="form-control" style="margin-bottom: 25px">
                                <option value="" disabled selected>Chọn xã - phường</option>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label for="street">{{ __('Address') }}<span>*</span></label>
                            <input type="text" id="street" class="street-first" name="street" required
                                value="{{ old('street') }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="email">{{ __('Email Address') }}<span>*</span></label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="phone">{{ __('Phone') }}<span>*</span></label>
                            <input type="text" id="phone" name="phone" required
                                value="{{ old('phone') }}">
                        </div>
                        <div class="col-lg-12">
                            <label for="note">{{ __('Note') }}</label>
                            <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                        </div>

                    </div>
                    @foreach ($errors->all() as $item)
                    <p style="color: red">{{ $item }}</p>
                    @endforeach
                </div>
                <div class="col-lg-6">
                    <div class="checkout-content">
                        <input type="text" placeholder="Coupon Code: null" value="@if (Session::get('coupon'))
                            @php
                                $cou = Session::get('coupon');
                                foreach($cou as $coupon){
                                    echo $coupon['coupon_name']." - ".$coupon['coupon_code'];
                                }
                            @endphp
                        @endif" disabled>
                    </div>
                    <div class="place-order">
                        <h4>{{ __('Your Order') }}</h4>
                        @php
                        $cart = Cart::content();
                        @endphp
                        <div class="order-total">
                            <ul class="order-table">
                                <li>{{ __('Product') }} <span>{{ __('Total') }}</span></li>
                                @foreach ($cart as $item)
                                <li class="fw-normal">{{ $item->name }} x {{ $item->qty }} -
                                    {{ $item->options->attribute }}
                                    <span>{{ number_format( $item->qty*$item->price) }}
                                        đ</span></li>
                                @endforeach
                                <li class="fw-normal">Subtotal <span>{{ Cart::subtotal() }} đ</span></li>

                                @php
                                if(Session::get('coupon')){
                                $cou = Session::get('coupon');
                                foreach($cou as $coupon){
                                if ($coupon['coupon_condition']==1){
                                $discount = filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION)*$coupon['coupon_number']/100;
                                }
                                else {
                                $discount = $coupon['coupon_number'];
                                }
                                }
                                }
                                @endphp

                                @isset($discount)
                                <li class="fw-normal">Discount <span>{{ number_format($discount) }} đ</span></li>
                                <input type="hidden" name="coupon_code" value="{{ $coupon['coupon_code'] }}">
                                <input type="hidden" name="discount" value="{{ $discount }}">
                                @if (filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION) >= $discount)
                                    <li class="total-price">Total <span>{{ number_format(filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                        FILTER_FLAG_ALLOW_FRACTION) - $discount) }} đ</span></li>
                                @else
                                <li class="total-price">Total <span>0 đ</span></li>
                                @endif
                                
                                @else
                                <li class="total-price">Total <span>{{ Cart::total() }} đ</span></li>
                                @endisset

                            </ul>
                            <div class="payment-check">
                                <div class="pc-item">
                                    <label for="pc-check">
                                        {{ __('CashPayment') }}
                                        <input type="radio" id="pc-check" name="payment" value="0" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="pc-item">
                                    <label for="pc-paypal">
                                        VNPAY
                                        <input type="radio" id="pc-paypal" name="payment" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <br>
                                <div class="payment-check">
                                    <label for="phone">{{ __('Bank') }} <span>({{ __('for vnpay') }})</span></label>
                                    <select name="bankcode" id="bankcode" class="form-control">
                                        <option value="">Mặc định </option>
                                        <option value="VNPAYQR">VNPAYQR</option>
                                        <option value="VNBANK">LOCAL BANK</option>
                                        <option value="IB">INTERNET BANKING</option>
                                        <option value="ATM">ATM CARD</option>
                                        <option value="INTCARD">INTERNATIONAL CARD</option>
                                        <option value="VISA">VISA</option>
                                        <option value="MASTERCARD"> MASTERCARD</option>
                                        <option value="JCB">JCB</option>
                                        <option value="UPI">UPI</option>
                                        <option value="VIB">VIB</option>
                                        <option value="VIETCAPITALBANK">VIETCAPITALBANK</option>
                                        <option value="SCB">Ngan hang SCB</option>
                                        <option value="NCB">Ngan hang NCB</option>
                                        <option value="SACOMBANK">Ngan hang SacomBank </option>
                                        <option value="EXIMBANK">Ngan hang EximBank </option>
                                        <option value="MSBANK">Ngan hang MSBANK </option>
                                        <option value="NAMABANK">Ngan hang NamABank </option>
                                        <option value="VNMART"> Vi dien tu VnMart</option>
                                        <option value="VIETINBANK">Ngan hang Vietinbank </option>
                                        <option value="VIETCOMBANK">Ngan hang VCB </option>
                                        <option value="HDBANK">Ngan hang HDBank</option>
                                        <option value="DONGABANK">Ngan hang Dong A</option>
                                        <option value="TPBANK">Ngân hàng TPBank </option>
                                        <option value="OJB">Ngân hàng OceanBank</option>
                                        <option value="BIDV">Ngân hàng BIDV </option>
                                        <option value="TECHCOMBANK">Ngân hàng Techcombank </option>
                                        <option value="VPBANK">Ngan hang VPBank </option>
                                        <option value="AGRIBANK">Ngan hang Agribank </option>
                                        <option value="MBBANK">Ngan hang MBBank </option>
                                        <option value="ACB">Ngan hang ACB </option>
                                        <option value="OCB">Ngan hang OCB </option>
                                        <option value="IVB">Ngan hang IVB </option>
                                        <option value="SHB">Ngan hang SHB </option>
                                    </select>
                                </div>
                                <br />
                                <div class="g-recaptcha" data-sitekey="{{env('CAPTCHA_KEY')}}"></div>
                                @if($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback" style="display:block">
                                    <strong>{{$errors->first('g-recaptcha-response')}}</strong>
                                </span>
                                @endif
                                @if (Session::get('message'))
                                <div class="alert alert-danger" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                                @php
                                Session::put('message',null);
                                @endphp
                                @endif
                            </div>
                            <div class="order-btn">

                                <button type="submit" class="site-btn place-btn">{{ __('Place Order') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Shopping Cart Section End -->
@push('add-script')
<script src="{{ URL::asset('frontend/js-function/checkout.js') }}"></script>
@endpush
@endsection