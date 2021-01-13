<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashi | Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ URL::asset('/backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/summernote/summernote-bs4.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/backend/css/custom.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/backend/css/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/backend/plugins/sweetalert2/sweetalert2.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css"> --}}

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <input type="hidden" name="my_url" value="{{ url('') }}">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ URL::to('/dashboard') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">1</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ URL::asset('/backend/dist/img/user1-128x128.jpg') }}" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ URL::to('/dashboard') }}" class="brand-link">
                <img src="{{ URL::asset('/backend/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">fashi</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ URL::asset('/backend/dist/img/user2-160x160.jpg') }}"
                            class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ URL::to('/ad/my-account/'.Auth::user()->admin_id) }}" class="d-block">
                            @php
                            echo Auth::user()->admin_name;
                            @endphp
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview menu-open">
                            <a href="{{ URL::to('/dashboard') }}" class="nav-link">
                                {{-- active --}}
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link category">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Danh mục
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @hasrole(['admin','author'])
                                <li class="nav-item">
                                    <a href="{{ URL::to('/add-category') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-warning"></i>
                                        <p>Thêm danh mục</p>
                                    </a>
                                </li>
                                @endhasrole
                                <li class="nav-item">
                                    <a href="{{ URL::to('/list-category') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-cyan"></i>
                                        <p>Liệt kê danh mục</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link brand">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    Thương hiệu
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @hasrole(['admin','author'])
                                <li class="nav-item">
                                    <a href="{{ URL::to('/add-brand') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-blue"></i>
                                        <p>Thêm thương hiệu</p>
                                    </a>
                                </li>
                                @endhasrole
                                <li class="nav-item">
                                    <a href="{{ URL::to('/list-brand') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-fuchsia"></i>
                                        <p>Liệt kê thương hiệu</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link product">
                                <i class="nav-icon fas fa-tree"></i>
                                <p>
                                    Sản phẩm
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @hasrole(['admin','author'])
                                <li class="nav-item">
                                    <a href="{{ URL::to('/add-product') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-fuchsia"></i>
                                        <p>Thêm sản phẩm</p>
                                    </a>
                                </li>
                                @endhasrole
                                <li class="nav-item">
                                    <a href="{{ URL::to('/list-product') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-indigo"></i>
                                        <p>Liệt kê sản phẩm</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to('/list-all-product-comment') }}" class="nav-link comment">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Bình luận
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link checkout">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Đơn hàng
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to('/admin-checkout-pending') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-blue"></i>
                                        <p>Đang chờ xữ lí</p>
                                    </a>
                                    <a href="{{ URL::to('/admin-checkout-success') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>Đã xác nhận</p>
                                    </a>
                                    <a href="{{ URL::to('/admin-checkout-cancel') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-danger"></i>
                                        <p>Đã huỷ</p>
                                    </a>
                                    <a href="{{ URL::to('/admin-checkout') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-cyan"></i>
                                        <p>Tất cả đơn hàng</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link postt">
                                <i class="nav-icon far fa-newspaper"></i>
                                <p>
                                    Tin tức
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @hasrole(['admin','author'])
                                <li class="nav-item">
                                    <a href="{{ URL::to('/add-category-post') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm danh mục tin tức</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to('/add-post') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-cyan"></i>
                                        <p>Thêm bài viết</p>
                                    </a>
                                </li>
                                @endhasrole
                                <li class="nav-item">
                                    <a href="{{ URL::to('/category-post') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-blue"></i>
                                        <p>Liệt kê danh mục tin tức</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to('/all-post') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-fuchsia"></i>
                                        <p>Liệt kê bài viết</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to('/all-post-comment') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-orange"></i>
                                        <p>Bình luận bài viết</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">Sự kiện</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link statistic">
                                <i class="nav-icon far fa-envelope"></i>
                                <p>
                                    Doanh thu
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to('/admin-total-list-statistic') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-danger"></i>
                                        <p>Thống kê toàn bộ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to('/admin-today-list-statistic') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon text-exception"></i>
                                        <p>Hôm nay, tuần này</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @hasrole(['admin','author'])
                        <li class="nav-item">
                            <a href="{{ URL::to('/ad/view-coupon') }}" class="nav-link coupon">
                                <i class="nav-icon fas fa-percent"></i>
                                <p>
                                    Mã giảm giá
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to('/ad/deal') }}" class="nav-link deal">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>
                                    Deal
                                </p>
                            </a>
                        </li>
                        @endhasrole
                        @hasrole(['admin','author'])
                        <li class="nav-header">Trang tĩnh</li>
                        <li class="nav-item">
                            <a href="{{ URL::to('/ad/faq') }}" class="nav-link faq">
                                <i class="nav-icon fas fa-question-circle"></i>
                                <p>
                                    Faq
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to('/ad/home-slider') }}" class="nav-link homeslider">
                                <i class="nav-icon fas fa-photo-video"></i>
                                <p>
                                    Home Slider
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to('/page/contact') }}" class="nav-link contact">
                                <i class="nav-icon fas fa-id-card"></i>
                                <p>
                                    Contact page
                                </p>
                            </a>
                        </li>
                        @endhasrole
                        <li class="nav-header">Account</li>
                        @hasrole(['admin'])
                        <li class="nav-item">
                            <a href="{{ URL::to('/admin-account-customer') }}" class="nav-link customer">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>
                                    Customer
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to('/auth-users') }}" class="nav-link auth">
                                <i class="nav-icon fas fa-user-lock"></i>
                                <p>
                                    Auth
                                </p>
                            </a>
                        </li>
                        @endhasrole
                        <li class="nav-item">
                            <a href="{{ URL::to('/admin-logout') }}" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('admin_content')
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.0.5
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="{{ URL::asset('/backend/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/plugins/toastr/toastr.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ URL::asset('/backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ URL::asset('/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ URL::asset('/backend/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ URL::asset('/backend/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ URL::asset('/backend/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ URL::asset('/backend/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ URL::asset('/backend/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ URL::asset('/backend/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
    </script>
    <!-- Summernote -->
    <script src="{{ URL::asset('/backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ URL::asset('/backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('/backend/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{-- <script src="{{ URL::asset('/backend/dist/js/pages/dashboard.js') }}"></script> --}}
    <!-- AdminLTE for demo purposes -->
    <script src="{{ URL::asset('/backend/dist/js/demo.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ URL::asset('/backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    {{-- Ck editor --}}
    <script src={{ url('ckeditor/ckeditor.js') }}></script>
    @include('ckfinder::setup')
    {{-- Form vadilator --}}
    <script src="{{ URL::asset('/backend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/js/simple.money.format.js') }}"></script>
    {{-- Sweet alert --}}
    {{-- <script src="{{ URL::asset('backend/plugins/sweetalert2/sweetalert2.all.js') }}"></script> --}}
    <script src="{{ URL::asset('frontend/js/sweetalert.min.js') }}"></script>
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
    {{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js">
    </script> --}}
    <!-- DataTables -->
    <script src="{{ URL::asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('/backend/js/bootstrap-tagsinput.js') }}"></script>
    {{-- Custom js validate --}}
    <script src="{{ URL::asset('/backend/js/validate_custom.js') }}"></script>

    @stack('custom-scripts-ajax')
    @stack('custom-scripts')
    @stack('active-nav')
    <script>

        function ChangeToSlug()
        {
            var slug;
            //Lấy text từ thẻ input title 
            slug = document.getElementById("slug").value;
            slug = slug.toLowerCase();
            //Đổi ký tự có dấu thành không dấu
                slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
                slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
                slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
                slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
                slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
                slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
                slug = slug.replace(/đ/gi, 'd');
                //Xóa các ký tự đặt biệt
                slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
                //Đổi khoảng trắng thành ký tự gạch ngang
                slug = slug.replace(/ /gi, "-");
                //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
                //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
                slug = slug.replace(/\-\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-\-/gi, '-');
                slug = slug.replace(/\-\-\-/gi, '-');
                slug = slug.replace(/\-\-/gi, '-');
                //Xóa các ký tự gạch ngang ở đầu và cuối
                slug = '@' + slug + '@';
                slug = slug.replace(/\@\-|\-\@|\@/gi, '');
                //In slug ra textbox có id “slug”
            document.getElementById('convert_slug').value = slug;
        }
        $(function(){
            $('.money').simpleMoneyFormat();
        })
    
    </script>
</body>

</html>