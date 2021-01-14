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
                    @isset($product->category->categoryParent->category_name)
                    <a
                        href="{{ URL::to('/category/'.$product->category->categoryParent->category_slug) }}">{{ $product->category->categoryParent->category_name }}</a>
                    @endisset
                    <a
                        href="{{ URL::to('/category/'.$product->category->category_slug) }}">{{ $product->category->category_name }}</a>
                    <span>{{ $product->product_name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Product Shop Section Begin -->
<section class="product-shop spad page-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="filter-widget">
                    <h4 class="fw-title">{{ __('Categories') }}</h4>
                    <ul class="filter-catagories">
                        @foreach ($category as $item)
                        <li><a href="{{ URL::to('/category/'.$item->category_slug) }}">{{ $item->category_name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <form action="{{ URL::to('/filter-range') }}" method="GET">
                    <div class="filter-widget">
                        <h4 class="fw-title">{{ __('Brand') }}</h4>
                        <div class="fw-brand-check">
                            @foreach ($brand as $item)
                            <div class="bc-item">
                                <label for="{{ $item->brand_id }}">
                                    {{ $item->brand_name }}
                                    <input type="checkbox" name="brand[]" id="{{ $item->brand_id }}"
                                        value="{{ $item->brand_id }}">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-widget">

                        <h4 class="fw-title">{{ __('Price') }}</h4>
                        <div class="filter-range-wrap">
                            <div class="range-slider">
                                <div class="price-input">
                                    <input type="text" id="minamount" name="min_price">
                                    <input type="text" id="maxamount" name="max_price">
                                    <span> k</span>
                                </div>
                            </div>
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="1" data-max="10000">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn filter-btn">{{ __('Filter') }}</button>

                    </div>
                </form>


                <div class="filter-widget">
                    <h4 class="fw-title">Tags</h4>
                    <div class="fw-tags">
                        @isset ($tag)
                        @foreach ($tag as $key=>$value)
                        <a href="{{ URL::to('/tag/'.$key) }}">{{ $key }}</a>
                        @endforeach
                        @endisset
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="product-pic-zoom">
                            <img class="product-big-img lazy"
                                data-src="{{ URL::asset('uploads/product/'.$product->product_image) }}" />
                            <div class="zoom-icon">

                                @if ($product->product_quantity==0)
                                <img src="{{ URL::asset('/frontend/img/sold-out.png') }}" width="80">
                                @else
                                <i class="fa fa-search-plus"></i>
                                @endif

                            </div>

                        </div>
                        <div class="product-thumbs">
                            <div class="product-thumbs-track ps-slider owl-carousel">
                                <div class="pt"
                                    data-imgbigurl="{{ URL::asset('uploads/product/'.$product->product_image) }}">
                                    <img class="lazy"
                                        data-src="{{ URL::asset('uploads/product/'.$product->product_image) }}" />
                                </div>
                                @foreach ($product->gallery as $item)
                                <div class="pt" data-imgbigurl="{{ URL::asset('uploads/gallery/'.$item->path) }}">
                                    <img class="lazy" data-src="{{ URL::asset('uploads/gallery/'.$item->path) }}" />
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="product-details">
                            <div class="pd-title">
                                <span>{{ $product->category->category_name }}</span>
                                <h3>{{ $product->product_name }}</h3>
                                {{-- <a href="#" class="heart-icon"><i class="icon_heart_alt"></i></a> --}}
                            </div>
                            <div class="pd-rating">
                                @php
                                $rating_p = round($product->rating->avg('rating'));
                                @endphp
                                @if ($product->
                                rating->avg('rating'))
                                @for ($i = 1; $i <= $rating_p; $i++) <i class="fa fa-star"></i>
                                    @endfor
                                    @for ($i = 1; $i <= 5-$rating_p; $i++) <i class="fa fa-star-o"></i>
                                        @endfor
                                        <span>({{ $product->rating->count() }})</span>
                                        @endif

                            </div>
                            <div class="pd-desc">
                                <p>{!! $product->product_desc !!}</p>
                                <h4 class="price-price-discount" data-price="{{ $product->product_price_discount }}">
                                    @if ($product->product_price_discount==$product->product_price)
                                    {{ number_format($product->product_price_discount) }} đ
                                    @else
                                    {{ number_format($product->product_price_discount) }} đ
                                    <span>{{ number_format($product->product_price) }} đ</span>
                                    @endif
                                </h4>
                            </div>
                            @if (count($product->attribute)>0)
                            <div class="pd-size-choose">
                                <div class="form-group">
                                    <select name="attribute" id="" class="form-control">
                                        @foreach ($product->attribute as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->extra_price }}">
                                            {{ __('Size') }}:
                                            {{ $item->size }} / {{ __('Color') }}:
                                            {{ $item->color }} / {{ __('Extra price') }}: +{{ $item->extra_price }}đ
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if ($product->product_quantity>0)
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="text" value="1" id="product_qty" min="1">
                                </div>
                                <a href="#" class="primary-btn pd-cart add-to-cart-icon"
                                    data-product_id="{{ $product->product_id }}">{{ __('Add To Cart') }}</a>
                            </div>
                            @endif
                            <ul class="pd-tags">
                                <li><span>{{ __('CATEGORIES') }}</span>: {{ $product->category->category_name }}
                                    @isset($product->category->categoryParent->category_name)
                                    ,
                                    {{ $product->category->categoryParent->category_name }}
                                    @endisset</li>
                                <li><span>{{ __('BRAND') }}</span>: {{ $product->brand->brand_name }}</li>
                                <li><span>{{ __('sales quantity') }}</span>: {{ $product->product_sales_quantity }}</li>
                                @if ($product->product_quantity==0)
                                <span class="btn btn-outline-warning font-weight-bold"
                                    style="cursor: default">{{ __('SOLD OUT') }}</span>

                                @else
                                <li><span>{{ __('AVAILABILITY') }}</span>: {{ $product->product_quantity}}</li>
                                @endif

                                <li><span>TAGS</span>: <div class="filter-widget">
                                        <div class="fw-tags">
                                            @isset($product->product_tag)
                                            @php
                                            $tags = explode(",", $product->product_tag);
                                            @endphp
                                            @foreach ($tags as $item)
                                            <a href="{{ url('/tag/'.url_slug($item) ) }}">{{ $item }}</a>
                                            @endforeach
                                            @endisset
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="pd-share">
                                <div class="p-code">ID : {{ $product->product_id }}</div>
                                <div class="pd-social">
                                    <a href="#"><i class="ti-facebook"></i></a>
                                    <a href="#"><i class="ti-twitter-alt"></i></a>
                                    <a href="#"><i class="ti-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-tab">
                    <div class="tab-item">
                        <ul class="nav" role="tablist">
                            <li>
                                <a class="active" data-toggle="tab" href="#tab-1" role="tab">{{ __('DESCRIPTION') }}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab-2" role="tab">{{ __('SPECIFICATIONS') }}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab-3" role="tab">{{ __('Customer Reviews') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-item-content">
                        <div class="tab-content">
                            <div class="tab-pane fade-in active" id="tab-1" role="tabpanel">
                                <div class="product-content">
                                    <div class="row">
                                        <div>
                                            {!! $product->product_content !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-2" role="tabpanel">
                                <div class="specification-table">
                                    <table>
                                        <tr>
                                            <td class="p-catagory">{{ __('Customer Rating') }}</td>
                                            <td>
                                                <div class="pd-rating">
                                                    @php
                                                    $rating_p = round($product->rating->avg('rating'));
                                                    @endphp
                                                    @if ($product->
                                                    rating->avg('rating'))
                                                    @for ($i = 1; $i <= $rating_p; $i++) <i class="fa fa-star"></i>
                                                        @endfor
                                                        @for ($i = 1; $i <= 5-$rating_p; $i++) <i class="fa fa-star-o">
                                                            </i>
                                                            @endfor
                                                            <span>({{ $product->rating->count() }})</span>
                                                            @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-catagory">{{ __('PRICE') }}</td>
                                            <td>
                                                <div class="p-price">{{ number_format($product->product_price) }} đ
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-catagory">{{ __('AVAILABILITY') }}</td>
                                            <td>
                                                <div class="p-stock">{{ $product->product_quantity }}
                                                    {{ __('in stock') }}</div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="p-catagory">ID</td>
                                            <td>
                                                <div class="p-code">{{ $product->product_id }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-3" role="tabpanel">
                                <div class="customer-review-option" id="customer-review-option">
                                    <h4>@isset($comment)
                                        <span class="count_comment">{{ $comment->count() }}</span>
                                        Comments!
                                        @endisset</h4>
                                    <div class="comment-option">
                                        @isset($comment)
                                        @foreach ($comment as $item)
                                        <div class="co-item">
                                            <div class="avatar-pic">
                                                @if ($item->customer->customer_image == null)
                                                <div class="logo-customer">
                                                    <span>{{ $item->customer->customer_name[0] }}</span>
                                                </div>
                                                @else
                                                <img src="{{ $item->customer->customer_image }}"
                                                    style="max-width: 50px;max-height: 50px" alt="">
                                                @endif
                                            </div>
                                            <div class="avatar-text">
                                                <div class="at-rating">
                                                    @if ($item->rating)
                                                    @for ($i = 1; $i <= $item->rating->rating; $i++)
                                                        <i class="fa fa-star"></i>
                                                        @endfor
                                                        @for ($i = 1; $i <= 5-$item->rating->rating; $i++)
                                                            <i class="fa fa-star-o"></i>
                                                            @endfor
                                                            @endif
                                                </div>
                                                <h5>{{ $item->customer->customer_name }}
                                                    <span>{{ $item->comment_time }}</span>
                                                </h5>
                                                <div class="at-reply">{{ $item->comment_content }}</div>
                                                <div class="at-reply">
                                                    <a href="#" class="comment-reply" data-id="{{ $item->comment_id }}"
                                                        data-name="{{ $item->customer->customer_name }}">Reply</a>
                                                    @if (Session::get('customer_id')==$item->customer->customer_id)
                                                    <a href="#" class="comment-remove"
                                                        data-id="{{ $item->comment_id }}">- Remove</a>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        @if (isset($item->childComment))
                                        @foreach ($item->childComment as $child)
                                        <div class="co-item ml-5 pl-2">
                                            <div class="avatar-pic">
                                                <span><i class="fa fa-reply" style="color: #ff98008a;
                                                    transform: rotate(180deg);"></i></span>
                                            </div>
                                            <div class="avatar-pic">
                                                @if ($child->customer->customer_image == null)
                                                <div class="logo-customer">
                                                    <span>{{ $child->customer->customer_name[0] }}</span>
                                                </div>
                                                @else
                                                <img src="{{ $child->customer->customer_image }}"
                                                    style="max-width: 50px;max-height: 50px" alt="">
                                                @endif
                                            </div>
                                            <div class="avatar-text">
                                                <div class="at-rating">
                                                    @if ($child->rating)
                                                    @for ($i = 1; $i <= $child->rating->rating; $i++)
                                                        <i class="fa fa-star"></i>
                                                        @endfor
                                                        @for ($i = 1; $i <= 5-$child->rating->rating; $i++)
                                                            <i class="fa fa-star-o"></i>
                                                            @endfor
                                                            @endif
                                                </div>
                                                <h5>{{ $child->customer->customer_name }}
                                                    <span>{{ $child->comment_time }}</span>
                                                </h5>
                                                <div class="at-reply">{{ $child->comment_content }}</div>
                                                @if (Session::get('customer_id') == $child->customer->customer_id)
                                                <div class="at-reply"><a href="#" class="comment-remove"
                                                        data-id="{{ $child->comment_id }}">Remove</a></div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                        @endforeach
                                        @endisset
                                    </div>

                                    @php
                                    $customer_id = Session::get('customer_id');
                                    @endphp

                                    @isset($customer_id)
                                    <div class="personal-rating">
                                        <h6>{{ __('Your Rating') }}</h6>
                                        <div class="rating">
                                            <ul class="list-inline" title="Rating" style="display: inline">
                                                @for ($i = 1; $i <= 5; $i++) @php if($i<=$rating){
                                                    $color='color:#ffcc00;' ; }else{ $color='color:#ccc;' ; } @endphp
                                                    <li title="Rating" id="{{ $product->product_id }}-{{ $i }}"
                                                    style="cursor: pointer;font-size: 30px;display: inline;{{ $color }}"
                                                    class="rating-ajax" data-index="{{ $i }}"
                                                    data-product_id="{{ $product->product_id }}"
                                                    data-rating="{{ $rating }}">
                                                    &#9733;
                                                    </li>
                                                    @endfor
                                            </ul>
                                        </div>
                                    </div>
                                    @endisset

                                    <div class="leave-comment">
                                        <h4>{{ __('Leave A Comment') }}</h4>
                                        <form action="#" class="comment-form">
                                            <div class="row">
                                                <form action="" id="">
                                                    @csrf
                                                    @if ($customer_id)
                                                    <div class="col-lg-12 float-left">
                                                        <input type="text" name="reply_to" disabled
                                                            value="{{ __('Reply all') }}" style="width: 70%" data-id="">
                                                        <button class="btn btn-default cancel_reply"
                                                            style="width: 20%">{{ __('Cancel reply') }}</button>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <textarea placeholder="Messages"
                                                            name="comment_content"></textarea>
                                                        <button type="submit" class="site-btn" name="comment_submit"
                                                            data-product_id="{{ $product->product_id }}">{{ __('Send message') }}</button>
                                                    </div>
                                                    @else
                                                    <span>{{ __('Please login to give comment!') }}</span>
                                                    @endif
                                                </form>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Shop Section End -->

<!-- Related Products Section End -->
<div class="related-products spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>{{ __('Related Products') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($related_products as $item)
            <div class="col-lg-3 col-sm-6">
                <div class="product-item">
                    <div class="pi-pic">
                        <img class="lazy" data-src="{{ URL::asset('uploads/product/'.$item->product_image) }}"
                            id="wishlist_product_img_{{ $item->product_id }}" />
                        {{-- <img src="{{ URL::asset('uploads/product/'.$item->product_image) }}" alt=""> --}}
                        {{-- <div class="icon">
                            <i class="icon_heart_alt"></i>
                        </div> --}}
                        <ul>
                            <li class="w-icon active"><a href="#" class="primary-btn pd-cart"
                                    data-product_id="{{ $item->product_id }}"><i class="icon_bag_alt"></i></a></li>

                            <li class="quick-view"><a href="{{ URL::to('/product/'.$item->product_slug) }}">+
                                    {{__('View detail')}}</a></li>
                            <li class="w-icon"><a href="#" class="button_wishlist" id="{{ $item->product_id }}"
                                    onclick="add_wishlist(this.id)"> <i class="icon_heart_alt"></i></a></li>
                        </ul>
                    </div>
                    <div class="pi-text">
                        <div class="catagory-name">{{ $item->category->category_name }}</div>
                        <a href="{{ URL::to('/product/'.$item->product_slug) }}"
                            id="wishlist_product_url_{{ $item->product_id }}">
                            <h5 id="wishlist_product_name_{{ $item->product_id }}">{{ $item->product_name }}</h5>
                        </a>
                        <div class="product-price">
                            @if ($item->product_price_discount==$item->product_price)
                            <var id="wishlist_product_price_{{ $item->product_id }}">{{ number_format($item->product_price_discount) }}
                                đ</var>
                            @else
                            <Var id="wishlist_product_price_{{ $item->product_id }}">{{ number_format($item->product_price_discount) }}
                                đ</Var>
                            <span>{{ number_format($item->product_price) }}
                                đ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Related Products Section End -->
@push('add-script')
<script src="{{ URL::asset('frontend/js-function/product.js') }}"></script>
@endpush
@endsection