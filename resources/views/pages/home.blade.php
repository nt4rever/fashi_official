@extends('layout',['category_navbar'=>$category_navbar])

@section('content')

<form action="">
    @csrf
</form>
<!-- Hero Section Begin -->
<section class="hero-section">
    <div class="hero-items owl-carousel">

        @isset($home_slider)
        @foreach ($home_slider as $item)
        <div class="single-hero-items set-bg"
            data-setbg="{{ $item->home_slider_image }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        {!! $item->home_slider_desc !!}
                        <a href="{{ URL::to('/shop') }}" class="primary-btn">{{ __('Shop Now') }}</a>
                    </div>
                </div>
                <div class="off-card">
                    {!! $item->home_slider_sale !!}
                </div>
            </div>
        </div>
        @endforeach
        @endisset

    </div>
</section>
<!-- Hero Section End -->

<!-- Banner Section Begin -->
<div class="banner-section spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="single-banner">
                    <img class="lazy" data-src="{{ URL::asset('frontend/img/banner-1.jpg')}}" />
                    <div class="inner-text">
                        <h4>Men’s</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="single-banner">
                    <img class="lazy" data-src="{{ URL::asset('frontend/img/banner-2.jpg')}}" />
                    <div class="inner-text">
                        <h4>Women’s</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="single-banner">
                    <img class="lazy" data-src="{{ URL::asset('frontend/img/banner-3.jpg')}}" />
                    <div class="inner-text">
                        <h4>Kid’s</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner Section End -->
@php
$numerical_order = 0;
@endphp

@foreach ($category as $item=>$value)
@if ($value->category_status==0)

@php
$numerical_order++;
@endphp

<!-- Women Banner Section Begin -->
<section class="women-banner spad">
    <div class="container-fluid">
        <div class="row">

            @if ($numerical_order % 2)
            <div class="col-lg-3">
                <div class="product-large set-bg"
                    data-setbg="{{$value->category_image}}">
                    <h2>{{ $value->category_name }}</h2>
                    <a href="{{ URL::to('/category/'.$value->category_slug) }}">{{ __('Discover More') }}</a>
                </div>
            </div>
            <div class="col-lg-8 offset-lg-1">
                @else
                <div class="col-lg-8">
                    @endif
                    <div class="filter-control">
                        <ul>
                            @foreach ($value->categoryChildrent as $itemChildren)
                            @if ($itemChildren->category_status==0)
                            <li>
                                <a href="{{ URL::to('/category/'.$itemChildren->category_slug) }}">
                                    {{ $itemChildren->category_name }}
                                </a>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="product-slider owl-carousel">
                        @foreach ($value->categoryChildrent as $itemChildren)
                        @if ($itemChildren->category_status==0)
                        @foreach ($itemChildren->products as $item2)
                        <div class="product-item">
                            <div class="pi-pic">
                                <img class="lazy" data-src="{{ $item2->product_image}}"
                                    id="wishlist_product_img_{{ $item2->product_id }}" />
                                @if ($item2->product_price_discount!=$item2->product_price)
                                <div class="sale">Sale</div>
                                @endif
                                @if ($item2->product_quantity==0)
                                <div class="icon">
                                    <img src="{{ URL::asset('/frontend/img/sold-out.png') }}" style="width: 80px">
                                </div>
                                @endif

                                <ul>
                                    <li class="w-icon active"><a href="#" class="add_to_cart"
                                            data-product_id="{{ $item2->product_id }}"><i class="icon_bag_alt"></i></a>
                                    </li>
                                    <li class="quick-view"><a href="#" class="home_quick_view"
                                            data-id="{{ $item2->product_id }}">{{ __('Quick view') }} +</a></li>
                                    <li class="w-icon"><a href="#" class="button_wishlist" id="{{ $item2->product_id }}"
                                            onclick="add_wishlist(this.id)"> <i class="icon_heart_alt"></i></a></li>
                                </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">{{ $item2->category->category_name }}</div>
                                <a href="{{ URL::to('/product/'.$item2->product_slug) }}"
                                    id="wishlist_product_url_{{ $item2->product_id }}">
                                    <h5 id="wishlist_product_name_{{ $item2->product_id }}">{{ $item2->product_name }}
                                    </h5>
                                </a>
                                @if ($item2->product_price_discount==$item2->product_price)
                                <div class="product-price" id="wishlist_product_price_{{ $item2->product_id }}">
                                    {{ number_format($item2->product_price_discount) }} đ
                                </div>
                                @else
                                <div class="product-price">
                                    <var id="wishlist_product_price_{{ $item2->product_id }}">{{ number_format($item2->product_price_discount) }}
                                        đ</var>
                                    <span>{{ number_format($item2->product_price) }}
                                        đ</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @endif
                        @endforeach
                    </div>
                </div>

                @if (!($numerical_order % 2))
                <div class="col-lg-3 offset-lg-1">
                    <div class="product-large set-bg"
                        data-setbg="{{ $value->category_image }}">
                        <h2>{{ $value->category_name }}</h2>
                        <a href="{{ URL::to('/category/'.$value->category_slug) }}">{{ __('Discover More') }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
</section>
<!-- Women Banner Section End -->
@endif
@endforeach

<script>
    var deal = [];
</script>

@isset($deal)
@foreach ($deal as $item)
<!-- Deal Of The Week Section Begin-->
<section class="deal-of-week spad mb-3" style="background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);">
    {{-- {{ URL::asset('uploads/product/'.$item->product->product_image) }} --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-6 text-center">
                <div class="section-title">
                    {{-- <h2>Deal Of The Week</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed<br /> do ipsum dolor sit amet,
                    consectetur adipisicing elit </p> --}}
                    {!! $item->deal_desc !!}
                    <div class="product-price">
                        {{ number_format($item->product->product_price_discount) }} đ
                        <span>/ {{ $item->product->category->category_name }}</span>
                    </div>
                </div>
                <div class="countdown-timer" id="countdown{{ $item->deal_id }}">
                    <div class="cd-item">
                        <span>56</span>
                        <p>Days</p>
                    </div>
                    <div class="cd-item">
                        <span>12</span>
                        <p>Hrs</p>
                    </div>
                    <div class="cd-item">
                        <span>40</span>
                        <p>Mins</p>
                    </div>
                    <div class="cd-item">
                        <span>52</span>
                        <p>Secs</p>
                    </div>
                </div>
                <a href="{{ URL::to('product/'.$item->product->product_slug) }}"
                    class="primary-btn">{{ __('Shop Now') }}</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ $item->product->product_image }}" alt=""
                    style="max-height:100% ">
            </div>
        </div>
    </div>
</section>
<!-- Deal Of The Week Section End -->
<script>
    // var timerdate = "{{ $item->deal_time }}"; 
var newItem = {
    'id':'#countdown{{ $item->deal_id }}',
    'timerdate':"{{ $item->deal_time }}"
}
deal.push(newItem);
</script>
@endforeach
@endisset



<!-- Instagram Section Begin -->
<div class="instagram-photo">
    <div class="insta-item set-bg" data-setbg="{{ URL::asset('frontend/img/insta-1.jpg') }}">
        <div class="inside-text">
            <i class="ti-instagram"></i>
            <h5><a href="#">instagram</a></h5>
        </div>
    </div>
    <div class="insta-item set-bg" data-setbg="{{ URL::asset('frontend/img/insta-2.jpg') }}">
        <div class="inside-text">
            <i class="ti-instagram"></i>
            <h5><a href="#">instagram</a></h5>
        </div>
    </div>
    <div class="insta-item set-bg" data-setbg="{{ URL::asset('frontend/img/insta-3.jpg') }}">
        <div class="inside-text">
            <i class="ti-instagram"></i>
            <h5><a href="#">instagram</a></h5>
        </div>
    </div>
    <div class="insta-item set-bg" data-setbg="{{ URL::asset('frontend/img/insta-4.jpg') }}">
        <div class="inside-text">
            <i class="ti-instagram"></i>
            <h5><a href="#">instagram</a></h5>
        </div>
    </div>
    <div class="insta-item set-bg" data-setbg="{{ URL::asset('frontend/img/insta-5.jpg') }}">
        <div class="inside-text">
            <i class="ti-instagram"></i>
            <h5><a href="#">instagram</a></h5>
        </div>
    </div>
    <div class="insta-item set-bg" data-setbg="{{ URL::asset('frontend/img/insta-6.jpg') }}">
        <div class="inside-text">
            <i class="ti-instagram"></i>
            <h5><a href="#">instagram</a></h5>
        </div>
    </div>
</div>
<!-- Instagram Section End -->

<!-- Latest Blog Section Begin -->
<section class="latest-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>{{ __('From The Blog') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($post_home as $item)
            <div class="col-lg-4 col-md-6">
                <div class="single-latest-blog">
                    <img src="{{ $item->post_image}}" alt="">
                    <div class="latest-text">
                        <div class="tag-list">
                            <div class="tag-item">
                                <i class="fa fa-calendar-o"></i>
                                {{ date('d/m/Y',strtotime($item->created_at)) }}
                            </div>
                            <div class="tag-item">
                                <i class="fa fa-eye"></i>
                                {{ $item->post_views }}
                            </div>
                        </div>
                        <a href="{{ URL::to('/blog/view/'.$item->post_slug) }}">
                            <h4>{{ $item->post_title }}</h4>
                        </a>
                        <p>{!! $item->post_desc !!}</p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        <div class="benefit-items">
            <div class="row">
                <div class="col-lg-4">
                    <div class="single-benefit">
                        <div class="sb-icon">
                            <img src="{{ URL::asset('frontend/img/icon-1.png')}}" alt="">
                        </div>
                        <div class="sb-text">
                            <h6>Free Shipping</h6>
                            <p>For all order over 99$</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-benefit">
                        <div class="sb-icon">
                            <img src="{{ URL::asset('frontend/img/icon-2.png')}}" alt="">
                        </div>
                        <div class="sb-text">
                            <h6>Delivery On Time</h6>
                            <p>If good have prolems</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-benefit">
                        <div class="sb-icon">
                            <img src="{{ URL::asset('frontend/img/icon-1.png')}}" alt="">
                        </div>
                        <div class="sb-text">
                            <h6>Secure Payment</h6>
                            <p>100% secure payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-home">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Quick view</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container-fluid modal-load-quick-view">

                </div>
                <div class="modal-footer float-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</section>
<!-- Latest Blog Section End -->
@push('add-script')
<script src="{{ URL::asset('frontend/js-function/home.js') }}"></script>
<script>
    $(function(){
        // let data = JSON.parse(deal);
        for (i = 0; i < deal.length; i++) {
            $(deal[i].id).countdown(deal[i].timerdate, function (event) {
            $(this).html(event.strftime("<div class='cd-item'><span>%D</span> <p>Days</p> </div>" + "<div class='cd-item'><span>%H</span> <p>Hrs</p> </div>" + "<div class='cd-item'><span>%M</span> <p>Mins</p> </div>" + "<div class='cd-item'><span>%S</span> <p>Secs</p> </div>"));
        });
        }
        
    })
</script>
@endpush
@endsection