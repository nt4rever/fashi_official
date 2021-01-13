@extends('layout')
@section('content')
<!-- Blog Details Section Begin -->
<section class="blog-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-details-inner">
                    @if ($post)
                    <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                    <div class="blog-detail-title">
                        <h2>{{ $post->post_title }}</h2>
                        <p>{{ $post->category->category_post_name }} <span>-
                                {{ date('d/m/Y', strtotime($post->created_at)) }}</span></p>
                    </div>
                    <div class="blog-large-pic">
                        <img src="{{ URL::asset('uploads/post/'.$post->post_image) }}" alt="">
                    </div>
                    <div class="blog-detail-desc">
                        {!! $post->post_content !!}
                    </div>

                    <div class="tag-share">
                        <div class="blog-share">
                            <span>Share:</span>
                            <div class="social-links">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="blog-post">
                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                @isset($post->prev)
                                @php
                                $previous = $post->prev;
                                @endphp
                                <a href="{{ URL::to('/blog/view/'.$previous->post_slug) }}" class="prev-blog">
                                    <div class="pb-pic">
                                        <i class="ti-arrow-left"></i>
                                        <img src="{{ URL::asset('uploads/post/'.$previous->post_image) }}" alt="">
                                    </div>
                                    <div class="pb-text">
                                        <span>Previous Post:</span>
                                        <h5>{{ $previous->post_title }}</h5>
                                    </div>
                                </a>
                                @endisset

                            </div>
                            <div class="col-lg-5 offset-lg-2 col-md-6">
                                @isset($post->next)
                                @php
                                $next = $post->next;
                                @endphp
                                <a href="{{ URL::to('/blog/view/'.$next->post_slug) }}" class="next-blog">
                                    <div class="nb-pic">
                                        <img src="{{ URL::asset('uploads/post/'.$next->post_image) }}" alt="">
                                        <i class="ti-arrow-right"></i>
                                    </div>
                                    <div class="nb-text">
                                        <span>Next Post:</span>
                                        <h5>{{ $next->post_title }}</h5>
                                    </div>
                                </a>
                                @endisset
                            </div>
                        </div>
                    </div>
                    <div id="all_comment">
                        @if ($post->comment)
                        @foreach ($post->comment as $item)
                        <div class="posted-by mb-2">
                            <div class="pb-pic">
                                <img src="{{ $item->customer->customer_image }}" alt="" width="80">
                            </div>
                            <div class="pb-text">
                                <h5><span style="font-weight: 600">{{ $item->customer->customer_name }}</span></h5>
                                <span class="text-muted small">{{ $item->time }}</span>

                                <p>{!! $item->content !!}</p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="leave-comment">
                        @if (Session::get('customer_name'))
                        <input type="hidden" name="customer_id" value="{{ Session::get('customer_id') }}">
                        <h4>Leave A Comment</h4>
                        <form action="#" class="comment-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <textarea placeholder="Messages" name="comment_content"></textarea>
                                    <button type="reset" class="site-btn send_comment">Send message</button>
                                </div>
                            </div>
                        </form>
                        @else
                        <p style="font-weight: bold">Login to leave a comment!</p>
                        @endif

                    </div>
                    @else
                    <span>Không tồn tại bài viết này!</span>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Section End -->
@push('active_li_nav')
<script>
    $(function(){
            $('.nav-menu ul li').removeClass('active');
            $('.nav-menu ul li').eq(2).addClass('active');
            $(document).on('click','.send_comment', function(){
                var url = $('#myurl').attr('url');
                // get time client
                function AddZero(num) {
                    return (num >= 0 && num < 10) ? "0" + num : num + "";
                }
                var now = new Date();
                var strDateTime = [[AddZero(now.getDate()),
                AddZero(now.getMonth() + 1),
                now.getFullYear()].join("/"),
                [AddZero(now.getHours()),
                AddZero(now.getMinutes())].join(":"),
                now.getHours() >= 12 ? "PM" : "AM"].join(" ");

                //set variable
                var comment_time = strDateTime;
                var _token = $("input[name = '_token']").val();
                var post_id = $("input[name=post_id]").val();
                var customer_id = $("input[name=customer_id]").val();
                var content = $("textarea[name=comment_content]").val();
                if (content == "") {
                    swal("Please fill content!");
                    return false;
                }


                $.ajax({
                    type: "POST",
                    cache: false,
                    url: url + "/add-post-comment",
                    data: {
                        post_id: post_id,
                        content: content, comment_time: comment_time,customer_id:customer_id, _token: _token
                    },
                    dataType: "html",
                    success: function (data) {
                        $('#all_comment').html(data);
                    }
                    ,
                    error: function (error) {
                        alert("Error!")
                    }
                });

                //clear value form
                $("textarea[name = 'comment_content']").val("");
                return false;
            });
        });
</script>
@endpush
@endsection