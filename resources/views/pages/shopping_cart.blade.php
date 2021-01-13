@extends('layout')
@section('content')
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text product-more">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a>
                    <span>{{ __('Shopping Cart') }}</span>
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
<section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cart-table">
                    @php
                    $cart = Cart::content();
                    @endphp
                    @if($cart->count()>0)
                    <table>
                        <thead class="text-nowrap">
                            <tr>
                                <th>{{ __('Image') }}</th>
                                <th class="p-name">{{ __('Product Name') }}</th>
                                <th>{{ __('PRICE') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Attribute') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th><i class="ti-close"></i></th>
                            </tr>
                        </thead>
                        <tbody class="table-cart-body text-nowrap">

                            @foreach ($cart as $item)
                            <tr>
                                <td class="cart-pic first-row"><img
                                        src="{{ URL::asset('uploads/product/'.$item->options->image) }}" alt=""
                                        style="width: 100px"></td>
                                <td class="cart-title first-row">
                                    <a href="{{ URL::to('/product/'.$item->options->slug) }}">
                                        <h5>{{ $item->name }}</h5>
                                    </a>
                                </td>
                                <td class="p-price first-row">{{ number_format($item->price) }} đ</td>
                                <td class="qua-col first-row">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input type="text" value="{{ $item->qty }}" data-price="{{ $item->price }}"
                                                data-rowid="{{ $item->rowId }}" disabled>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-price first-row text-uppercase">{{ $item->options->attribute }}</td>
                                <td class="total-price first-row">{{ number_format($item->qty*$item->price) }} đ</td>
                                <td class="close-td first-row"><i class="ti-close delete-cart"
                                        data-rowid="{{ $item->rowId }}"></i></td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                    @else
                    <p>Your shopping cart is empty!</p>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="cart-buttons">
                            <a href="{{ URL::to('/shop') }}"
                                class="primary-btn continue-shop">{{ __('Continue shopping') }}</a>
                            <a href="{{ URL::to('/cart-destroy') }}"
                                class="primary-btn up-cart">{{ __('Delete cart') }}</a>
                        </div>
                        <div class="discount-coupon">
                            <h6>{{ __('Discount Codes') }}</h6>
                            <form action="{{ URL::to('/check-coupon') }}" class="coupon-form" method="POST">
                                @csrf
                                <input type="text" placeholder="Enter your codes" name="coupon" required>
                                <button type="submit" class="site-btn coupon-btn">{{ __('Apply') }}</button>
                            </form>
                            <div class="coupon_area">
                                @if (Session::get('message'))
                                    <section class='alert alert-dark'>{{session('message')}}</section>
                                @php
                                Session::put('message',null)
                                @endphp
                                @endif

                                @if (Session::get('coupon'))
                                @foreach (Session::get('coupon') as $item)
                                <br>
                                @if ($item['coupon_condition']==1)
                                <p>{{ $item['coupon_name'] }}:
                                    -{{ number_format(filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                    FILTER_FLAG_ALLOW_FRACTION)*$item['coupon_number']/100)}}
                                    đ</p>
                                @php
                                $discount = filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION)*$item['coupon_number']/100;
                                @endphp
                                @else
                                <p>{{ $item['coupon_name'] }}: - {{ number_format($item['coupon_number']) }} đ</p>
                                @php
                                $discount = $item['coupon_number'];
                                @endphp
                                @endif
                                @endforeach
                                <div class="cart-buttons">
                                    <a href="{{ URL::to('/cancel-use-coupon') }}" class="primary-btn up-cart">Delete
                                        coupon</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-4">
                        <div class="proceed-checkout">
                            <ul>
                                <li class="subtotal">Subtotal <span>@php
                                        echo Cart::subtotal();
                                        @endphp đ</span></li>
                                @isset ($discount)
                                <li class="subtotal">Discount <span>-{{ number_format($discount) }} Đ</span></li>
                                @if (filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION) >= $discount)
                                <li class="cart-total">Total <span>@php
                                        echo number_format(filter_var(Cart::total(), FILTER_SANITIZE_NUMBER_FLOAT,
                                        FILTER_FLAG_ALLOW_FRACTION) - $discount);
                                        @endphp đ</span></li>
                                @else
                                <li class="cart-total">Total <span>0 đ</span></li>
                                @endif

                                @else
                                <li class="cart-total">Total <span>@php
                                        echo Cart::total();
                                        @endphp đ</span></li>
                                @endisset
                            </ul>
                            <a href="#" class="proceed-btn">{{ __('PROCEED TO CHECK OUT') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shopping Cart Section End -->
@push('add-script')
<script src="{{ URL::asset('frontend/js-function/cart.js') }}"></script>
@endpush
@endsection