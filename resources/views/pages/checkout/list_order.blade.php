@extends('layout')
@section('content')
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text product-more">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a>
                    <span>{{ __('Order History') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="">
    @csrf
</form>
<!-- Breadcrumb Section Begin -->
<section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cart-table">

                    @isset($cart)
                    @foreach ($cart as $item_1)
                    <div class="accordion p-2" id="accordionExample">
                        <div class="card">
                            <a class="active" data-toggle="collapse" data-target="#collapse{{ $item_1->order_id }}"
                                style="cursor: pointer">
                                @php

                                @endphp
                                <div class="card-heading active p-2"
                                    style="font-weight: 600;background-image: linear-gradient(240deg, #fdfbfb 0%, #ebedee 100%);">

                                    {{ __('Order') }}: {{ $item_1->order_id }} -
                                    {{ date_format($item_1->created_at,"Y/m/d H:i:s") }} -
                                    @php
                                    if($item_1->order_status==0){
                                    echo '<span class="badge badge-secondary">Đang chờ xử lý</span>';
                                    }
                                    else if($item_1->order_status==1){
                                    echo '<span class="badge badge-success">Đã xác nhận</span>';
                                    }
                                    else if($item_1->order_status==2){
                                    echo '<span class="badge badge-success">Đã giao hàng</span>';
                                    }
                                    else {
                                    echo '<span class="badge badge-danger">Đã huỷ</span>';
                                    }
                                    if($item_1->message){
                                    echo ' <span class="badge badge-info">Message</span>';
                                    }
                                    @endphp
                                    <span class="float-right"><i class="fa fa-angle-down"></i></span>
                                </div>
                            </a>
                            <div id="collapse{{ $item_1->order_id }}" class="collapse" data-parent="#accordionExample">
                                <section class="checkout-section spad">
                                    <div class="container">
                                        <form action="#" class="checkout-form">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="place-order">
                                                        <div class="order-total">
                                                            <ul class="order-table">
                                                                <li>{{ __('Order') }} <span>{{ $item_1->order_id  }}</span></li>
                                                                @if ($item_1->discount!="")
                                                                <li class="fw-normal">Coupon
                                                                    <span>{{ $item_1->coupon_code }}</span>
                                                                </li>
                                                                <li class="fw-normal">Sub total
                                                                    <span>{{ number_format($item_1->order_total) }}
                                                                        đ</span></li>
                                                                <li class="fw-normal">{{ __('Discount') }}
                                                                    <span>-{{ number_format($item_1->discount)}}
                                                                        đ</span></li>
                                                                @if (filter_var($item_1->order_total,
                                                                FILTER_SANITIZE_NUMBER_FLOAT,
                                                                FILTER_FLAG_ALLOW_FRACTION) >= $item_1->discount)
                                                                <li class="total-price">{{ __('Total') }}
                                                                    <span>{{  number_format(filter_var($item_1->order_total, FILTER_SANITIZE_NUMBER_FLOAT,
                                                                                FILTER_FLAG_ALLOW_FRACTION) - $item_1->discount) }} đ</span>
                                                                    @else
                                                                <li class="total-price">{{ __('Total') }} <span>0 đ</span>
                                                                    @endif

                                                                </li>
                                                                @else
                                                                <li class="total-price">{{ __('Total') }}
                                                                    <span>{{ number_format($item_1->order_total) }}
                                                                        đ</span></li>
                                                                @endif
                                                            </ul>
                                                            <div class="order-btn">
                                                                @php
                                                                if($item_1->order_status==0){
                                                                echo '<button class="btn btn-secondary" disabled>Đang xử
                                                                    lý</button><a href="#" class="cancel-order"
                                                                    data-id="'.$item_1->order_id.'"><button
                                                                        class="btn btn-danger ml-1">Huỷ đơn
                                                                        này</button></a>';
                                                                }
                                                                else if ($item_1->order_status==1){
                                                                echo '<button class="btn btn-success" disabled>Đã xác
                                                                    nhận</button></button><a href="#"
                                                                    class="receive-order"
                                                                    data-id="'.$item_1->order_id.'"><button
                                                                        class="btn btn-info ml-1 mt-1">Xác nhận đã nhận
                                                                        hàng</button></a>';
                                                                }
                                                                else if ($item_1->order_status==2){
                                                                echo '<button class="btn btn-info" disabled>Đã giao
                                                                    hàng</button>';
                                                                }
                                                                else {
                                                                echo '<button class="btn btn-danger" disabled>Đã
                                                                    huỷ</button>';
                                                                }
                                                                @endphp
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="font-weight-bold">{{ __('Payment method') }}:
                                                            @if($item_1->payment_id==1)
                                                            VNPAY
                                                            @else
                                                            {{ __('Cash on delivery') }}
                                                            @endif</p>
                                                        <p class="font-weight-bold">{{ __('Shipping information') }}:</p>
                                                        <p>{{ __('Name') }}: {{ $item_1->shipping->shipping_name }}</p>
                                                        <p>{{ __('Address') }}: {{ $item_1->shipping->shipping_address }}</p>
                                                        <p>{{ __('Phone') }}: {{ $item_1->shipping->shipping_phone }}</p>
                                                        <p>{{ __('Email') }}: {{ $item_1->shipping->shipping_email }}</p>
                                                        <p>{{ __('Note') }}: {{ $item_1->shipping->shipping_note }}</p>
                                                    </div>
                                                </div>

                                                <div class="col-lg-8">

                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Image') }}</th>
                                                                <th class="p-name">{{ __('Product Name') }}</th>
                                                                <th>{{ __('Price') }}</th>
                                                                <th>{{ __('Quantity') }}</th>
                                                                <th>{{ __('Attribute') }}</th>
                                                                <th>{{ __('Total') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-cart-body">
                                                            @foreach ($item_1->order_detail as $item)
                                                            <tr>
                                                                <td class="cart-pic first-row"><img
                                                                        src="{{ $item->product->product_image }}"
                                                                        alt="" style="width: 50px"></td>
                                                                <td class="cart-title first-row">
                                                                    <h5>{{ $item->product_name }}</h5>
                                                                </td>
                                                                <td class="p-price first-row">
                                                                    {{ number_format($item->product_price) }} đ</td>
                                                                <td class="qua-col first-row">
                                                                    <h5>{{ $item->product_sales_quantity }}</h5>
                                                                </td>
                                                                <td class="p-price first-row text-uppercase">
                                                                    {{ $item->order_attribute }}</td>
                                                                <td class="total-price first-row">
                                                                    {{ number_format($item->product_sales_quantity*$item->product_price) }}
                                                                    đ</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <p>{{ __('Time') }}: {{ $item_1->created_at }}</p>
                                                    @if ($item_1->message)
                                                    <div class="alert alert-warning" role="alert">
                                                        {{ __('Message') }}: {{ $item_1->message }}
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <!-- Shopping Cart Section End -->

                    @endforeach
                    @endisset
                </div>
                <div class="loading-more">
                    <span>
                        {!! $cart->render('vendor.pagination.name') !!}
                    </span>
                </div>
            </div>
        </div>
</section>
@push('add-script')
<script>
    $(function(){
            $('.cancel-order').click(function(){
                swal({
                        title: "Bạn chắc chắn muốn huỷ đơn hàng?",
                        text: "Mục này không thể hoàn tác!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                var id = $(this).data('id');
                                var _token = $('input[name="_token"]').val();
                                var this_p = $(this).parent();
                                topbar.show();

                                (function step() {
                                    setTimeout(function () {
                                        if (topbar.progress('+.01') < 1) step()
                                    }, 16)
                                })()
                                $.ajax({
                                    type: "POST",
                                    cache: false,
                                    url: "{{url('/cancel-order')}}",
                                    data: { id: id, _token: _token },
                                    dataType: "html",
                                    success: function (data) {
                                        topbar.hide();
                                        this_p.html(data);
                                    }
                                    ,
                                    error: function (error) {
                                        topbar.hide();
                                        swal("Erorr!");
                                    }
                                });
                            }
                    });
                return false;
            });

            $('.receive-order').click(function(){
                swal({
                        title: "Bạn đã nhận được đơn hàng?",
                        text: "Mục này không thể hoàn tác!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                var id = $(this).data('id');
                                var _token = $('input[name="_token"]').val();
                                var this_p = $(this).parent();
                                topbar.show();

                                (function step() {
                                    setTimeout(function () {
                                        if (topbar.progress('+.01') < 1) step()
                                    }, 16)
                                })()
                                $.ajax({
                                    type: "POST",
                                    cache: false,
                                    url: "{{url('/receive-order')}}",
                                    data: { id: id, _token: _token },
                                    dataType: "html",
                                    success: function (data) {
                                        topbar.hide();
                                        this_p.html(data);
                                    }
                                    ,
                                    error: function (error) {
                                        topbar.hide();
                                        swal("Erorr!");
                                    }
                                });
                            }
                    });
                return false;
            });
        });
</script>
@endpush
@endsection