<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fashi Template">
    <meta name="keywords" content="Fashi, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fashi | Shop</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/themify-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/elegant-icons.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ URL::asset('frontend/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/animate.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/custom.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/sweetalert2/sweetalert2.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/toastr/toastr.min.css') }}">
    <link rel="icon" href="{{ URL::asset('/frontend/img/icon.webp') }}" type="image/gif" sizes="16x16">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader" style="display: none"></div>
    </div>

    <input type="hidden" id="myurl" url="{{url('')}}" />

    <!-- Header Section Begin -->
    <header class="header-section">
        <div class="header-top">
            <div class="container">
                <div class="ht-left">
                    <div class="mail-service">
                        <i class=" fa fa-envelope"></i>
                        @if (!empty($contact))
                        {{ $contact->email }}
                        @endif
                    </div>
                    <div class="phone-service">
                        <i class=" fa fa-phone"></i>
                        @if (!empty($contact))
                        {{ $contact->phone }}
                        @endif
                    </div>
                </div>
                <div class="ht-right">
                    @php
                    $session_customer = Session::get('customer_name');
                    // $cus_img = Session::get('customer_img');
                    @endphp
                    @if ($session_customer)
                    
                    <span class="login-panel">
                        <button class="btn btn-dark btn-sm logout-cus">{{ $session_customer }}&nbsp;&nbsp;<i
                                class="fa fa-sign-out"></i></button>
                    </span>
                    @else
                    <a href="{{ URL::to('/login-customer') }}" class="login-panel"><i
                            class="fa fa-user"></i>{{ __('Login') }}</a>
                    @endif

                    <div class="lan-selector">
                        <select class="language_drop" name="countries" id="countries" style="width:300px;">
                            @if (Session::get('language')=='vn')
                            <option value='en' data-image="{{ URL::asset('frontend/img/us_16.png') }}"
                                data-imagecss="flag yt" data-title="English">English</option>
                            <option value='vn' data-image="{{ URL::asset('frontend/img/vn_16.png') }}"
                                data-imagecss="flag yt" data-title="English" selected>Vietnam</option>
                            @else
                            <option value='en' data-image="{{ URL::asset('frontend/img/us_16.png') }}"
                                data-imagecss="flag yt" data-title="English" selected>English</option>
                            <option value='vn' data-image="{{ URL::asset('frontend/img/vn_16.png') }}"
                                data-imagecss="flag yt" data-title="English">Vietnam</option>
                            @endif

                        </select>
                    </div>
                    <div class="top-social">
                        @if (!empty($contact))
                        {!! $contact->social !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="inner-header">
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <div class="logo">
                            <a href="{{ URL::to('/home') }}">
                                <img src="{{ URL::asset('frontend/img/logo.png') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7">
                        <form class="typeahead" role="search" action="{{ URL::to('/search-bar') }}" method="GET">
                            <div class="advanced-search">
                                <select name="category" id="" class="category-btn">
                                    <option value="0">{{ __('All Categories') }}</option>
                                    {!! $option_search !!}
                                </select>
                                <div class="input-group">
                                    <input type="search" name="keyword" class="search-input"
                                        placeholder="{{ __('What do you need?') }}" autocomplete="off" required>
                                    <button type="submit"><i class="ti-search"></i></button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="col-lg-1 text-right col-md-1 favorite_col" style="padding-right: 0px">
                        <ul class="nav-right">
                            <li class="cart-icon">
                                <a href="#">
                                    <i class="icon_heart_alt"></i>
                                    <span id="count-favorite-item">1</span>
                                </a>
                                <div class="cart-hover">
                                    <div class="select-items">
                                        <table>
                                            <tbody id="list-favorite-item">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="select-button">
                                        <a href="#" class="primary-btn view-card" id="delete-favorite-item">Delete</a>
                                    </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-2 text-left col-md-2 cart_col" style="padding-left: 0px" id="cart_mini">
                        <ul class="nav-right">
                            <li class="cart-icon">
                                <a href="{{ URL::to('/shopping-cart') }}">
                                    <i class="icon_bag_alt"></i>
                                    <span>{{ Cart::content()->count() }}</span>
                                </a>
                                <div class="cart-hover">
                                    @php
                                    $cart_nav = Cart::content();
                                    @endphp
                                    <div class="select-items">
                                        <table>
                                            <tbody>
                                                @if($cart_nav->count()>0)
                                                @foreach ($cart_nav as $item)
                                                <tr>
                                                    <td class="si-pic">
                                                        <img class="lazy"
                                                            data-src="{{ URL::asset('uploads/product/'.$item->options->image) }}"
                                                            width="100px" />
                                                    </td>
                                                    <td class="si-text">
                                                        <div class="product-selected">
                                                            <p>{{ number_format($item->price) }} đ x {{ $item->qty }}
                                                            </p>
                                                            <h6>{{ $item->name }}</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <p>Chưa có sản phẩm nào trong giỏ hàng!</p>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="select-total">
                                        <span>{{ __('total') }}:</span>
                                        <h5>{{ Cart::total() }} đ</h5>
                                    </div>
                                    <div class="select-button">
                                        <a href="{{ URL::to('/shopping-cart') }}" class="primary-btn view-card">VIEW
                                            CARD</a>
                                        <a href="{{ URL::to('/checkout') }}" class="primary-btn checkout-btn">CHECK
                                            OUT</a>
                                    </div>
                                </div>
                            </li>
                            <li class="cart-price">
                                {{ number_format(filter_var(Cart::total(),FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION)) }}
                                đ</li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
        <div class="nav-item">
            <div class="container">
                <div class="nav-depart">
                    <div class="depart-btn">
                        <i class="ti-menu"></i>
                        <span>{{ __('All departments') }}</span>
                        <ul class="depart-hover">
                            {!! $category_navbar !!}
                        </ul>
                    </div>
                </div>
                <nav class="nav-menu mobile-menu">
                    <ul>
                        <li class="active"><a href="{{ URL::to('/home') }}">{{ __('Home') }}</a></li>
                        <li><a href="{{ URL::to('/shop') }}">{{ __('Shop') }}</a></li>
                        <li><a href="{{ URL::to('/blog') }}">{{ __('Blog') }}</a></li>
                        <li><a href="{{ URL::to('/contact') }}">{{ __('Contact') }}</a></li>
                        <li><a href="{{ URL::to('/faq') }}">Faq</a></li>
                        <li><a href="#">{{ __('Pages') }}</a>
                            <ul class="dropdown">
                                <li><a href="{{ URL::to('/shopping-cart') }}">{{ __('Shopping Cart') }}</a></li>
                                @if ($session_customer)
                                <li><a href="{{ URL::to('/list-order-customer') }}">{{ __('Your order') }}</a></li>
                                <li><a href="{{ URL::to('/u/account') }}">{{ __('Account') }}</a></li>
                                <li><a href="#" class="logout-cus">{{ __('Logout') }}</a></li>
                                @else
                                <li><a href="{{ URL::to('/register-customer') }}">{{ __('Register') }}</a></li>
                                <li><a href="{{ URL::to('/login-customer') }}">{{ __('Login') }}</a></li>
                                @endif

                            </ul>
                        </li>
                    </ul>
                </nav>
                <div id="mobile-menu-wrap"></div>
            </div>
        </div>
    </header>
    <!-- Header End -->


    @yield('content')


    <!-- Partner Logo Section Begin -->
    <div class="partner-logo">
        <div class="container">
            <div class="logo-carousel owl-carousel">
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="{{ URL::asset('frontend/img/logo-carousel/logo-1.png') }}" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="{{ URL::asset('frontend/img/logo-carousel/logo-2.png') }}" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="{{ URL::asset('frontend/img/logo-carousel/logo-3.png') }}" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="{{ URL::asset('frontend/img/logo-carousel/logo-4.png') }}" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="{{ URL::asset('frontend/img/logo-carousel/logo-5.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Partner Logo Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-left">
                        <div class="footer-logo">
                            <a href="#"><img src="{{ URL::asset('frontend/img/footer-logo.png') }}" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: @if (!empty($contact))
                                {{ $contact->address }}
                                @endif</li>
                            <li>Phone: @if (!empty($contact))
                                {{ $contact->phone }}
                                @endif</li>
                            <li>Email: @if (!empty($contact))
                                {{ $contact->email }}
                                @endif</li>
                        </ul>
                        <div class="footer-social">
                            @if (!empty($contact))
                            {!! $contact->social !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <div class="footer-widget">
                        <h5>Information</h5>
                        <ul>
                            <li><a href="{{ URL::to('/contact') }}">About Us</a></li>
                            <li><a href="{{ URL::to('/shopping-cart') }}">Checkout</a></li>
                            <li><a href="{{ URL::to('/contact') }}">Contact</a></li>
                            <li><a href="https://www.facebook.com/levantanlop91">Serivius</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-widget">
                        <h5>My Account</h5>
                        <ul>
                            <li><a href="{{ URL::to('/u/account') }}">My Account</a></li>
                            <li><a href="https://www.facebook.com/levantanlop91">Contact</a></li>
                            <li><a href="{{ URL::to('/shopping-cart') }}">Shopping Cart</a></li>
                            <li><a href="{{ URL::to('/shop') }}">Shop</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="newslatter-item">
                        <h5>Join Our Newsletter Now</h5>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#" class="subscribe-form">
                            <input type="text" placeholder="Enter Your Mail">
                            <button type="button">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-reserved">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-text">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This template is made with <i class="fa fa-heart-o"
                                aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>.
                            Server powered by <a href="https://www.facebook.com/levantanlop91">4rever.nt</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </div>
                        <div class="payment-pic">
                            <img src="{{ URL::asset('frontend/img/payment-method.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
    <!-- Scroll Up -->
    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fa fa-level-up"></i></a>
    </div>

    {{-- <div class="zalo-chat-widget" data-oaid="579745863508352884" data-welcome-message="Rất vui khi được hỗ trợ bạn!"
        data-autopopup="0" data-width="350" data-height="420"></div>

    <script src="https://sp.zalo.me/plugins/sdk.js"></script> --}}
    <!-- Js Plugins -->
    <script src="{{ URL::asset('frontend/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/jquery.dd.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/jquery.slicknav.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/owl.carousel.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('frontend/lazyload/jquery.lazy.min.js')}}"></script>
    <script src="{{ URL::asset('frontend/lazyload/jquery.lazy.plugins.min.js')}}"></script>
    <script src="{{ URL::asset('frontend/js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/plugins/toastr/toastr.min.js') }}"></script>
    {{-- Form vadilator --}}
    <script src="{{ URL::asset('/backend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="{{ URL::asset('frontend/js/topbar.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/main.js') }}"></script>
    <script src="{{ URL::asset('frontend/js/custom.js') }}"></script>
    <script src="{{ URL::asset('frontend/js-function/layout.js') }}"></script>
    <script>
        $(function () {
            //category
        $('.button_wishlist').click(function(){
            return false;
        })
        $('.depart-hover li a').click(function () {
        var id = $(this).data('id');
        let url = "{{ route('getCategory', ':id') }}";
        url = url.replace(':id', id);
        window.location.href = url;
        return false;
        });

        //logout
        $('.logout-cus').click(function () {
        swal({
            title: "Are you sure?",
            text: "You will logout!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = "{{ url('/logout-customer') }}";
                }
            });
            return false;
        });

        $('.lazy').lazy({
            placeholder: "data:image/gif;base64,R0lGODlhEALAPQAPzl5uLr9Nrl8e7..."
        });

        $('select[name=countries]').on('change',function(){
            var url = $('#myurl').attr('url');
            window.location.href = url+'/language/'+$(this).val();
        });
    });
    </script>
    @stack('add-script')
    @stack('active_li_nav')
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v9.0'
        });
      };

      (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Your Chat Plugin code -->
    <div class="fb-customerchat" attribution=setup_tool page_id="409764229515442" theme_color="#e7ab3c"
        logged_in_greeting="Chào bạn, chúng tôi có thể giúp gì cho bạn?"
        logged_out_greeting="Chào bạn, chúng tôi có thể giúp gì cho bạn?">
    </div>
</body>

</html>