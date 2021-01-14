@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    @isset($this_cate)
                    <a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a>
                    @isset($this_cate->categoryParent->category_name)
                    <a
                        href="{{ URL::to('/category/'.$this_cate->categoryParent->category_slug) }}">{{ $this_cate->categoryParent->category_name }}</a>
                    @endisset
                    <span>{{ $this_cate->category_name }}</span>
                    @endisset

                    @isset($_GET['keyword'])
                    <a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a>
                    <span>Search '{{ $_GET['keyword'] }}'</span>
                    @endisset

                    @isset($_GET['min_price'])
                    <a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a>
                    <span>Filter '{{ $_GET['min_price'] }}k - {{ $_GET['max_price']  }}k'</span>
                    @endisset

                    @if (empty($_GET['keyword']) && empty($this_cate) && empty($_GET['min_price'] ))
                    <span>{{ __('Shop') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Product Shop Section Begin -->
<form action="">
    @csrf
</form>
<section class="product-shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">
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
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="product-show-option">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <div class="select-option">
                                <select class="sorting cus-sort">
                                    {{-- begin sort --}}
                                    {{-- default selected  --}}
                                    @isset($_GET['sortBy'])
                                    @if ($_GET['sortBy']=='price_asc')
                                    <option value="" disabled selected>{{ __('Price: Low to high') }}</option>
                                    @endif
                                    @if ($_GET['sortBy']=='price_desc')
                                    <option value="" disabled selected>{{ __('Price: High to low') }}</option>
                                    @endif
                                    @if ($_GET['sortBy']=='best_seller')
                                    <option value="" disabled selected>{{ __('Best seller') }}</option>
                                    @endif
                                    @if($_GET['sortBy']=='new')
                                    <option value="">{{ __('Newest Arrivals') }}</option>
                                    @endif
                                    @endisset
                                    {{-- option sort --}}
                                    @if (isset($_GET['keyword']) || isset($_GET['min_price']) || isset($_GET['sortBy']))
                                    @php
                                    $url = url()->full();
                                    $url = preg_replace('~(\?|&)'.'sortBy'.'=[^&]*~', '$1', $url);
                                    $url = preg_replace('~(\?|&)'.'page'.'=[^&]*~', '$1', $url);
                                    @endphp
                                    <option value="{{ $url }}&sortBy=new">{{ __('Newest Arrivals') }}</option>
                                    <option value="{{ $url }}&sortBy=price_asc">{{ __('Price: Low to high') }}</option>
                                    <option value="{{ $url }}&sortBy=price_desc">{{ __('Price: High to low') }}</option>
                                    <option value="{{ $url }}&sortBy=best_seller">{{ __('Best seller') }}</option>
                                    @else
                                    <option value="{{ url()->current() }}?sortBy=new">{{ __('Newest Arrivals') }}
                                    </option>
                                    <option value="{{ url()->current() }}?sortBy=price_asc">
                                        {{ __('Price: Low to high') }}</option>
                                    <option value="{{ url()->current() }}?sortBy=price_desc">
                                        {{ __('Price: High to low') }}</option>
                                    <option value="{{ url()->current() }}?sortBy=best_seller">
                                        {{ __('Best seller') }}</option>
                                    @endif
                                    {{-- end sort --}}
                                </select>
                                <select class="p-show show-qty">
                                    @if (isset($_GET['keyword']) || isset($_GET['min_price']) || isset($_GET['sortBy'])
                                    )
                                    @php
                                    $url = url()->full();
                                    $url = preg_replace('~(\?|&)'.'show'.'=[^&]*~', '$1', $url);
                                    $url = preg_replace('~(\?|&)'.'page'.'=[^&]*~', '$1', $url);
                                    @endphp
                                    <option value="" disabled selected>{{ __('Show') }}: {{ $product->count() }}
                                    </option>
                                    <option value="{{ $url }}&show=3">
                                        {{ __('Show') }}: 3</option>
                                    <option value="{{ $url }}&show=6">
                                        {{ __('Show') }}: 6</option>
                                    <option value="{{ $url }}&show=9">
                                        {{ __('Show') }}: 9</option>
                                    @else
                                    <option value="" disabled selected>{{ __('Show') }}: {{ $product->count() }}
                                    </option>
                                    <option value="{{ url()->current() }}?show=3">{{ __('Show') }}: 3</option>
                                    <option value="{{ url()->current() }}?show=6">{{ __('Show') }}: 6</option>
                                    <option value="{{ url()->current() }}?show=9">{{ __('Show') }}: 9</option>
                                    @endif
                                </select>
                                @isset($_GET['min_price'])
                                <select class="sorting ml-4">
                                    <option value="" disabled selected>
                                        {{ __('Filter') }}: {{ $_GET['min_price'] }}k - {{ $_GET['max_price'] }}k
                                    </option>
                                </select>
                                @endisset
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 text-right">
                            <p>{{ __('Show') }} {{ $product->count() }} {{ __('Of') }} {{ $product->total()  }}
                                {{ __('Product') }}</p>
                        </div>
                    </div>
                </div>
                <div class="product-list">
                    <div class="row">
                        @foreach ($product as $item)
                        <div class="col-lg-4 col-sm-6">
                            <div class="product-item">
                                <div class="pi-pic">
                                    <img class="lazy"
                                        data-src="{{ URL::asset('uploads/product/'.$item->product_image) }}"
                                        id="wishlist_product_img_{{ $item->product_id }}" />
                                    @if ($item->product_price_discount!=$item->product_price)
                                    <div class="sale pp-sale">Sale</div>
                                    @endif
                                    @if($item->product_quantity==0)
                                    <div class="icon">
                                        <img src="{{ URL::asset('/frontend/img/sold-out.png') }}" width="80">
                                    </div>
                                    @endif
                                    <ul>
                                        <li class="w-icon active"><a href="#" class="add_to_cart"
                                                data-product_id="{{ $item->product_id }}"><i
                                                    class="icon_bag_alt"></i></a>
                                        </li>
                                        <li class="quick-view"><a href="#" class="home_quick_view"
                                                data-id="{{ $item->product_id }}">{{ __('Quick view') }} +</a></li>
                                        <li class="w-icon"><a href="#" class="button_wishlist"
                                                id="{{ $item->product_id }}" onclick="add_wishlist(this.id)"> <i
                                                    class="icon_heart_alt"></i></a></li>
                                    </ul>
                                </div>
                                <div class="pi-text">
                                    <div class="catagory-name">{{ $item->category_name }}</div>
                                    <a href="{{ URL::to('/product/'.$item->product_slug) }}"
                                        id="wishlist_product_url_{{ $item->product_id }}">
                                        <h5 id="wishlist_product_name_{{ $item->product_id }}">{{ $item->product_name }}
                                        </h5>
                                    </a>
                                    @if ($item->product_price_discount==$item->product_price)
                                    <div class="product-price" id="wishlist_product_price_{{ $item->product_id }}">
                                        {{ number_format($item->product_price_discount) }} đ
                                    </div>
                                    @else
                                    <div class="product-price">
                                        <var id="wishlist_product_price_{{ $item->product_id }}">{{ number_format($item->product_price_discount) }}
                                            đ</var>
                                        <span>{{ number_format($item->product_price) }} đ</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if ($product->count()==0)
                        <div>
                            <h5>Không có sản phẩm nào
                                @isset($_GET['keyword'])
                                cho từ khoá <span class="text-muted">'{{ $_GET['keyword']}}'</span>
                                @endisset
                            </h5>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="loading-more">
                    <span>
                        {!! $product->render('vendor.pagination.name') !!}
                    </span>
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

<!-- Product Shop Section End -->
@push('add-script')
<script src="{{ URL::asset('frontend/js-function/shop.js') }}"></script>
@endpush
@endsection