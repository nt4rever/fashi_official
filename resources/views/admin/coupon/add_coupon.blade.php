@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Thêm mã giảm giá</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/ad/save-coupon') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="coupon_name">Tên mã giảm giá</label>
                            <input type="text" class="form-control" id="coupon_name" name="coupon_name"
                                placeholder="Free ship" required value="{{ old('coupon_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="coupon_code">Mã giảm giá</label>
                            <input type="text" class="form-control" id="coupon_code" name="coupon_code"
                                placeholder="FREESHIP01" required value="{{ old('coupon_code') }}">
                        </div>
                        <div class="form-group">
                            <label for="coupon_time">Số lượng mã</label>
                            <input type="text" class="form-control" id="coupon_time" name="coupon_time" placeholder="01"
                                required value="{{ old('coupon_time') }}">
                        </div>
                        <div class="form-group">
                            <label for="coupon_condition">Tính năng mã</label>
                            <select class="form-control" id="coupon_condition" name="coupon_condition">
                                <option value="0" disabled>Chọn tính năng</option>
                                <option value="1">Giảm theo phần trăm</option>
                                <option value="2">Giảm theo tiền</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="coupon_number">Nhập số % hoặc tiền giảm</label>
                            <input type="number" class="form-control" id="coupon_number" name="coupon_number"
                                placeholder="1000" required>
                        </div>
                        <div class="form-group">
                            <label>Thời hạn:</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" name="coupon_date_time"
                                    id="reservationtime" required>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group">
                            <label for="coupon_condition">Trạng thái</label></label>
                            <select class="form-control" id="coupon_status" name="coupon_status">
                                <option value="0">Hoạt động
                                </option>
                                <option value="1">Ẩn
                                </option>
                            </select>
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@push('custom-scripts')
<script>
    $(function(){
        $('#reservationtime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 30,
    locale: {
      format: 'YYYY-MM-DD HH:mm:ss'
    }
  })
    })
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.coupon').addClass('active');
        });
</script>
@endpush
@endsection