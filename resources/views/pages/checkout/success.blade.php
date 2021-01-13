@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text product-more">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a>
                    <a href="{{ URL::to('/shop') }}">Shop</a>
                    <span>Check Out</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Shopping Cart Section Begin -->
<section class="checkout-section spad">
    <div class="container">
        <form action="" class="checkout-form">
            <div class="row">
                <div class="col-lg-6">
                    <div class="checkout-content">
                        <h3>Order Success!</h3>
                    </div>
                    <div class="place-order">
                        <h4>Your Order</h4>
                        <div class="order-total">
                            <ul class="order-table">
                                <li>Product <span>Total</span></li>
                                @foreach ($cart as $item)
                                <li class="fw-normal">{{ $item->name }} x {{ $item->qty }} -
                                    {{ $item->options->attribute }}
                                    <span>{{ number_format( $item->qty*$item->price) }} đ</span></li>
                                @endforeach

                                @if (Session::get('coupon'))
                                @foreach (Session::get('coupon') as $item)
                                @if ($item['coupon_condition']==1)
                                @php
                                $discount = filter_var($order['order_total'], FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION)*$item['coupon_number']/100;
                                @endphp
                                @else
                                @php
                                $discount = $item['coupon_number'];
                                @endphp
                                @endif
                                @endforeach
                                <li class="total-price">Discount <span>{{ number_format($discount) }} đ</span></li>
                                @if (filter_var($order['order_total'], FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION)>=$discount)
                                <li class="total-price">Total
                                    <span>{{ number_format(filter_var($order['order_total'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION)-$discount) }}
                                        đ</span></li>
                                @else
                                <li class="total-price">Total <span>0 đ</span></li>
                                @endif

                                @else
                                <li class="total-price">Total <span>{{ number_format($order['order_total']) }} đ</span></li>
                                @endif
                                @php
                                Session::forget('coupon');
                                @endphp
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="checkout-content">
                        <h3>Thank you!</h3>
                    </div>
                    <div class="place-order">
                        <h4>Shipping infomation</h4>
                        <p>{{ $shipping['shipping_name'] }}</p>
                        <p>{{ $shipping['shipping_address'] }}</p>
                        <p>{{ $shipping['shipping_phone'] }}</p>
                        <p>{{ $shipping['shipping_email'] }}</p>
                        <p>{{ $shipping['shipping_note'] }}</p>
                        <a href="{{ URL::to('/home') }}" class="badge badge-dark"><i class="fa fa-home"></i> Back to
                            home</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Shopping Cart Section End -->
@push('add-script')
<script>
    $(function () {
    $('.nav-menu ul li').removeClass('active');
    $('.nav-menu ul li:contains(Shopping Cart)').addClass('active');
});
</script>
@endpush
@endsection