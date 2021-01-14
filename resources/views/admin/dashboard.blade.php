@extends('admin_layout')
@section('admin_content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $last_7_day }}</h3>

                        <p>Đơn hàng mới trong 7 ngày qua</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $visitor }}</h3>
                        <p>Visitor</p>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $online }}</h3>

                        <p>Admin đang online</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $online_customer }}</h3>

                        <p>Customer online</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-12">
                <!-- /.card -->
                <!-- solid sales graph -->

                <form action="">
                    @csrf
                </form>


                <div class="card card-info">
                    <div class="card-header">
                        <button type="button" class="btn btn-default" id="daterange-btn" name="select-btn-filter">
                            <i class="far fa-calendar-alt"></i> Chọn khoảng thời gian
                            <i class="fas fa-caret-down"></i>
                        </button>
                        <button class="btn btn-default" id="export-csv">Xuất CSV</button>
                        <span id="reportrange"></span>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart_area"
                                style="min-height: 250px; height: 250px; max-height: 550px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

            </section>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thống kê sản phẩm</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Lượt xem</th>
                                    <th>Đã bán</th>
                                    <th>Số lượng tồn</th>
                                </tr>
                            </thead>
                            <tbody>


                                @php
                                $stt = 0;
                                @endphp
                                @foreach ($product as $item)
                                <tr>
                                    <td>@php
                                        $stt++;
                                        echo $stt;
                                        @endphp</td>
                                    <td><a href="{{ URL::to('/product/'.$item->product_slug) }}"
                                            target="_blank">{{ $item->product_name }}</a></td>
                                    <td>{{ $item->product_views }}</td>
                                    <td>{{ $item->product_sales_quantity }}</td>
                                    <td>{{ $item->product_quantity }}</td>
                                </tr>
                                @endforeach


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Lượt xem</th>
                                    <th>Đã bán</th>
                                    <th>Số lượng tồn</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thống kê bài viết, tin tức</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên bài viết</th>
                                    <th>Lượt xem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $stt = 0;
                                @endphp
                                @foreach ($post as $item)
                                <tr>
                                    <td>@php
                                        $stt++;
                                        echo $stt;
                                        @endphp</td>
                                    <td><a href="{{ URL::to('/blog/view/'.$item->post_slug) }}"
                                            target="_blank">{{ $item->post_title }}</a></td>
                                    <td>{{ $item->post_views }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên bài viết</th>
                                    <th>Lượt xem</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@push('custom-scripts')
<script src="{{ URL::asset('/backend/js/dashboard.js') }}"></script>
@endpush
@endsection