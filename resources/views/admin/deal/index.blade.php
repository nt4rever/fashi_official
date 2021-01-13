@extends('admin_layout')
@section('admin_content')

<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Deal product</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Price Discount</th>
                                <th>Deal desc</th>
                                <th>Deal time</th>
                                <th>Trạng thái</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deal as $item)
                            <tr>
                                <td>{{ $item->product->product_name }}</td>
                                <td><img src="{{ URL::asset('uploads/product/'.$item->product->product_image) }}" alt=""
                                        width="80"></td>
                                <td>{{ number_format($item->product->product_price) }} đ</td>
                                <td>{{ number_format($item->product->product_price_discount) }} đ</td>
                                <td>{!! $item->deal_desc !!}</td>
                                <td>{{ $item->deal_time }}</td>
                                <td>
                                    @if ($item->deal_time >= date('Y-m-d H:i:s', time()) ) <span
                                        class="badge badge-success">Kích hoạt</span>
                                        @else
                                        <span class="badge badge-dark">Hết hạn</span>
                                        @endif
                                </td>
                                @hasrole(['admin','author'])
                                <td>
                                    <a href="{{ URL::to('/ad/edit-deal/'.$item->deal_id) }}" title="Xoá">
                                        <button type="button" class="btn btn-outline-info">
                                            <i class="fas fa-edit"></i></button></a>
                                    </button>
                                    <a href="{{ URL::to('/ad/delete-deal/'.$item->deal_id) }}"
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
                <a href="{{ URL::to('/ad/add-deal') }}"><button class="btn btn-outline-info">Thêm deal</button></a>
                @endhasrole
                {!! $deal->render('vendor.pagination.name') !!}
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
            $('a.nav-link.deal').addClass('active');
        });
</script>
@endpush
@endsection