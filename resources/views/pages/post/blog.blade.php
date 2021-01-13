@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> Home</a>
                    <span>Blog</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Blog Section Begin -->
<section class="blog-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1">
                <div class="blog-sidebar">
                    <div class="search-form">
                        <h4>Search</h4>
                        <form action="{{ URL::to('/blog/search') }}" method="get">
                            <input type="text" placeholder="Search . . .  " name="keyword" required autocomplete="off">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="blog-catagory">
                        <h4>Categories</h4>
                        <ul>
                            @foreach ($category_post as $item)
                            <li><a
                                    href="{{ URL::to('/blog/category/'.$item->category_post_slug) }}">{{ $item->category_post_name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="recent-post">
                        <h4>Recent Post</h4>
                        <div class="recent-blog">
                            @foreach ($recent_post as $item)
                            <a href="{{ URL::to('/blog/view/'.$item->post_slug) }}" class="rb-item" title="{{ $item->post_title }}">
                                <div class="rb-pic">
                                    <img src="{{ URL::asset('uploads/post/'.$item->post_image) }}" alt="">
                                </div>
                                <div class="rb-text">
                                    <h6>{{ $item->post_title }}</h6>
                                    <p>{{ $item->category->category_post_name }} <span>- {{ date('d/m/Y',strtotime($item->created_at)) }}</span></p>
                                </div>
                            </a>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="row">
                    @foreach ($all_post as $item)
                    <div class="col-lg-6 col-sm-6">
                        <div class="blog-item">
                            <div class="bi-pic">
                                <img src="{{ URL::asset('uploads/post/'.$item->post_image) }}" alt="">
                            </div>
                            <div class="bi-text">
                                <a href="{{ URL::to('/blog/view/'.$item->post_slug) }}">
                                    <h4>{{ $item->post_title }}</h4>
                                </a>
                                <p>{{ $item->category->category_post_name }} <span>-
                                        {{ date('d/m/Y',strtotime($item->created_at)) }}</span> - <span><i class="fa fa-eye"></i> {{ $item->post_views }}</span></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-lg-12">
                        <div class="loading-more">
                            <span>
                                {!! $all_post->render('vendor.pagination.name') !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section End -->
@push('add-script')
<script>
    $(function(){
            $('.nav-menu ul li').removeClass('active');
            $('.nav-menu ul li').eq(2).addClass('active');

        });
</script>
@endpush
@endsection