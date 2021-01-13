@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Đơn hàng:
                        @isset($all_order)
                        <span class="count_order">{{ $all_order->count() }}</span>
                        @endisset</h3>

                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        <form action="">
                            @csrf
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng (ID)</th>
                                <th>Thành tiền</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody id="order_data">
                            @isset($all_order)
                            @foreach ($all_order as $item)
                            <tr>
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->customer->customer_name }} <span
                                        class="text-muted small">({{ $item->customer_id }})</span></td>
                                @if ($item->discount!="")
                                @if (filter_var($item->order_total, FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION) >= $item->discount)
                                <td>{{  number_format(filter_var($item->order_total, FILTER_SANITIZE_NUMBER_FLOAT,
                                        FILTER_FLAG_ALLOW_FRACTION) - $item->discount) }} đ</td>
                                @else
                                <td>0 đ</td>
                                @endif

                                @else
                                <td>{{ number_format($item->order_total) }} đ</td>
                                @endif
                                <td>
                                    @php
                                    if($item->order_status==0 && $item->payment_id==0){
                                    echo '<span class="badge badge-secondary p-1">Đang xữ lý</span>';
                                    }
                                    else if($item->order_status==0 && $item->payment_id==1){
                                    echo '<span class="badge badge-secondary p-1">Đang xữ lý</span> <span
                                        class="badge badge-info p-1">Đã thanh toán</span>';
                                    }
                                    else if ($item->order_status==1 && $item->payment_id==0){
                                    echo '<span class="badge badge-success p-1">Đã xác nhận</span>';
                                    }
                                    else if ($item->order_status==1 && $item->payment_id==1){
                                    echo '<span class="badge badge-success p-1">Đã xác nhận</span> <span
                                        class="badge badge-info p-1">Đã thanh toán</span>';
                                    }
                                    else if($item->order_status==2){
                                    echo '<span class="badge badge-info p-1">Đã nhận hàng</span>';
                                    }
                                    else{
                                    if( $item->payment_id==1){
                                    echo '<span class="badge badge-danger p-1">Đã huỷ</span> <span
                                        class="badge badge-info p-1">VNPAY</span>';
                                    }
                                    else{
                                    echo '<span class="badge badge-danger p-1">Đã huỷ</span>';
                                    }
                                    }
                                    @endphp

                                </td>
                                <td>{{ $item->created_at }}</td>
                                <td><a href="{{ URL::to('/admin-checkout-detail/'.$item->order_id) }}"><button
                                            class="btn btn-outline-warning"><i class="far fa-eye"></i></button></a></td>
                            </tr>

                            @endforeach
                            @else
                            @push('custom-scripts')
                            <script>
                                swal('Không có đơn hàng nào!');
                            </script>
                            @endpush
                            @endisset
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                {!! $all_order->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>

@push('custom-scripts-ajax')
<script>
    jQuery( document ).ready(function( $ ) {

    //Use this inside your document ready jQuery 
    $(window).on('popstate', function() {
       location.reload(true);
    });
 
 });
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.checkout').addClass('active');
            $('a.nav-link.checkout').parent().addClass('menu-open')
        });
</script>
@endpush
@endsection