@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Đơn hàng
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<span style='color: #ffeb3b'><br>".$message.'</span>';
                        Session::put('message', null);
                        }
                        @endphp
                    </h3>
                    <div class="card-tools">
                        <form action="">
                            @csrf
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap table-primary">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Khách hàng (ID)</th>
                                <th>Coupon</th>
                                <th>Discount</th>
                                <th>Thành tiền</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->customer->customer_name }} <span
                                        class="text-muted small">({{ $order->customer_id }})</span></td>
                                @if ($order->discount!="")
                                <td>{{ $order->coupon_code }}</td>
                                <td>{{ number_format($order->discount) }} đ</td>
                                @if (filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT,
                                FILTER_FLAG_ALLOW_FRACTION) >= $order->discount)
                                <td>{{  number_format(filter_var($order->order_total, FILTER_SANITIZE_NUMBER_FLOAT,
                                        FILTER_FLAG_ALLOW_FRACTION) - $order->discount) }} đ</td>
                                @else
                                <td>0 đ</td>
                                @endif

                                @else
                                <td>null</td>
                                <td>null</td>
                                <td>{{ number_format($order->order_total) }} đ</td>
                                @endif
                                <td class="order-status">
                                    @php
                                    if($order->order_status==0){
                                    echo '<span class="badge badge-secondary p-1">Đang xữ lý</span>';
                                    }
                                    else if ($order->order_status==1){
                                    echo '<span class="badge badge-success p-1">Đã xác nhận</span>';
                                    }
                                    else if ($order->order_status==2){
                                    echo '<span class="badge badge-info p-1">Đã giao hàng</span>';
                                    }
                                    else {
                                    echo '<span class="badge badge-danger p-1">Đã huỷ</span>';
                                    }
                                    @endphp
                                </td>
                                <td>
                                    @if ($order->payment_id==0 )
                                    Thanh toán khi nhận hàng
                                    @else
                                    Thanh toán VNPAY
                                    @endif
                                </td>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            {{-- shipping --}}
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Thông tin vận chuyển</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap table-success">
                        <thead>
                            <tr>
                                <th>Tên người nhận</th>
                                <th>Địa chỉ</th>
                                <th>SĐT</th>
                                <th>Email</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $order->shipping->shipping_name }}</td>
                                <td>{{ $order->shipping->shipping_address }}</td>
                                <td>{{ $order->shipping->shipping_phone }}</td>
                                <td>{{ $order->shipping->shipping_email }}</td>
                                <td>{{ $order->shipping->shipping_note }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Chi tiết đơn hàng</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ URL::to('/update-order-detail/'.$order->order_id) }}" method="post">
                    @csrf
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-warning">
                            <thead>
                                <tr>
                                    <th>ID Sản phẩm</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Thuộc tính</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_detail as $item)
                                <tr>
                                    <td>{{ $item->product_id }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->order_attribute }}</td>
                                    <td>{{ number_format($item->product_price) }} đ</td>
                                    <td style="width: 100px"><input type="number" name="{{ $item->order_detail_id }}"
                                            value="{{ $item->product_sales_quantity }}" min="1" class="form-control">
                                    </td>
                                    <td>{{ number_format($item->product_price*$item->product_sales_quantity) }} đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <button onclick="return confirm('Cap nhat muc nay!')" type="submit"
                        class="btn btn-sm btn-danger float-right m-2">Cập nhật <span class="small">(Mã giảm giá không được áp dụng khi cập nhật đơn hàng)</span></button>
                </form>

                <!-- /.card-body -->
            </div>
            <div class="alert alert-secondary text_message" role="alert">
                Message: {{ $order->message }}
            </div>


            <div class="card-tools mb-5 text-center">
                <form action="">
                    @csrf
                </form>
                <a href="{{ URL::to('/admin-checkout-pending') }}"><button class="btn btn-secondary">Quay
                        lại</button></a>
                <a target="_blank" href="{{ URL::to('/print-pdf-checkout/'. $order->order_id) }}"
                    class="btn btn-default">In đơn hàng</a>
                <button class="btn btn-info" data-toggle="modal" data-target="#givemessage">Gửi thông báo</button>
                <button class="btn btn-success order-confrim" data-id="{{ $order->order_id }}">Xác nhận đơn
                    hàng</button>
                <button class="btn btn-warning order-cancel" data-id="{{ $order->order_id }}">Huỷ đơn hàng</button>
            </div>

            <!-- /.card -->
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="givemessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea name="message-content" class="form-control" id="" cols="30" rows="6"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary give-message"
                        data-id="{{ $order->order_id }}">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts-ajax')
<script>
    $(function () {
    $('.give-message').click(function(){
        var message = $('textarea[name=message-content]').val();
        var id = $(this).data('id');
        var _token = $("input[name='_token']").val();
        $.ajax({
                type: "POST",
                cache: false,
                url: "{{url('/give-message-checkout')}}",
                data: { id: id,message:message, _token: _token },
                dataType: "html",
                success: function (data) {
                    $('.text_message').html('Message: '+data);
                    $('#givemessage').modal('hide');
                }
                ,
                error: function (error) {
                    swal("Erorr!");
                }
            });
        return false;
    });

    $('.order-confrim').click(function () {
        var id = $(this).data('id');
        var _token = $("input[name='_token']").val();

        swal({
            title: "Chốt đơn hàng này?",
            text: "Xác nhận đơn hàng!",
            buttons: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/admin-confrim-order')}}",
                        data: { id: id, _token: _token },
                        dataType: "json",
                        success: function (data) {
                            // console.log(data);
                            $('.order-status').html(data.data);
                            swal(data.message.toString(), {
                                icon: "info",
                            });
                        }
                        ,
                        error: function (error) {
                            swal("Erorr!");
                        }
                    });

                }
            });
    });

    $('.order-cancel').click(function () {
        var id = $(this).data('id');
        var _token = $("input[name='_token']").val();

        swal({
            title: "Huỷ đơn hàng này?",
            text: "Huỷ đơn!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/admin-cancel-order')}}",
                        data: { id: id, _token: _token },
                        dataType: "json",
                        success: function (data) {
                            // console.log(data);
                            $('.order-status').html(data.data);
                            swal(data.message.toString(), {
                                icon: "info",
                            });
                        }
                        ,
                        error: function (error) {
                            swal("Erorr!");
                        }
                    });

                }
            });
    });
});
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.checkout').addClass('active');
        });
</script>
@endpush
@endsection