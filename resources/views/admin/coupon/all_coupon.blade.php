@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mã giảm giá</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        {{-- <form action="{{ URL::to('/search-brand') }}" method="POST">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            @csrf
                            <input type="text" class="form-control float-right" placeholder="Search" name="keyword"
                                required>

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        </form> --}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Tên mã giảm giá</th>
                                <th>Mã giảm giá</th>
                                <th>Số lượng mã</th>
                                <th>Tính năng mã</th>
                                <th>% hoặc số tiền giảm</th>
                                <th>Bắt đầu</th>
                                <th>Kết thúc</th>
                                <th>Trạng thái</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_coupon as $item => $value)
                            <tr>
                                <td>{{ $value->coupon_name }}</td>
                                <td>{{ $value->coupon_code }}</td>
                                <td>{{ $value->coupon_time }}</td>

                                <td>
                                    @if ($value->coupon_condition==1)
                                    Giảm thêm phần trăm
                                    @else
                                    Giảm theo số tiền
                                    @endif
                                </td>
                                <td>{{ $value->coupon_number }}</td>
                                <td>{{ $value->coupon_start }}</td>
                                <td>{{ $value->coupon_end }}</td>
                                <td>
                                    @if ($value->coupon_time==0)
                                    <span class="badge badge-danger">Hết mã</span>
                                    @endif
                                    @if (($value->coupon_start<date('Y-m-d H:i:s', time())) && ($value->
                                        coupon_end>date('Y-m-d H:i:s', time())))
                                        <span class="badge badge-success">Kích hoạt</span>
                                        @else
                                        <span class="badge badge-dark">Hết hạn</span>
                                        @endif

                                        @if ($value->coupon_status==0)
                                        <span class="badge badge-info">Hiển thị</span>
                                        @else
                                        <span class="badge badge-dark">Ẩn</span>
                                        @endif
                                </td>
                                @hasrole(['admin','author'])
                                <td>
                                    <a href="{{ URL::to('/ad/edit-coupon/'.$value->coupon_id) }}" title="Xoá">
                                        <button type="button" class="btn btn-outline-info">
                                            <i class="fas fa-edit"></i></button></a>
                                    </button>
                                    <a href="{{ URL::to('/ad/delete-coupon/'.$value->coupon_id) }}"
                                        onclick="return confirm('Bạn chắc chắn muốn xoá!')" title="Xoá">
                                        <button type="button" class="btn btn-outline-warning">
                                            <i class="fas fa-trash-alt"></i></button></a>
                                    </button>
                                </td>
                                @endhasrole
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                @hasrole(['admin','author'])
                <a href="{{ URL::to('/ad/add-coupon') }}"><button class="btn btn-outline-info">Thêm mã giảm
                        giá</button></a>
                @endhasrole
                {!! $all_coupon->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

</div>
@push('custom-scripts-ajax')

@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.coupon').addClass('active');
        });
</script>
@endpush
@endsection