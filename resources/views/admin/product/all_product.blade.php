@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách sản phẩm</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        <form action="{{ URL::to('/search-product') }}" method="POST">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                @csrf
                                <select name="select_quantity_view" class="form-control">
                                    <option value="">Hiển thị: {{ count($all_product)  }}</option>
                                    <option value="9">9</option>
                                    <option value="18">18</option>
                                    <option value="27">27</option>
                                    <option value="36">36</option>
                                    <option value="100">100</option>
                                </select>
                                &nbsp;
                                &nbsp;
                                <input type="text" class="form-control float-right" placeholder="Search" name="keyword"
                                    required>

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" id="myTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                {{-- <th>Mô tả</th> --}}
                                <th>Danh mục <span class="text-muted small">(ID)</span></th>
                                <th>Thương hiệu <span class="text-muted small">(ID)</span></th>
                                <th>Giá <span class="text-muted small">(Giá gốc)</span></th>
                                <th>Giảm giá <span class="text-muted small">(Giá hiển thị)</span></th>
                                <th>Số lượng <span class="text-muted small">(Đã bán)</span></th>
                                <th>Ảnh sản phẩm</th>
                                <th>Rating</th>
                                <th>Thư viện ảnh</th>
                                <th>Bình luận</th>
                                <th>Thuộc tính</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <style>
                            #product_order .ui-state-highlight {
                                padding: 24px;
                                background-color: #ffffcc;
                                border: 1px dotted #ccc;
                                cursor: move;
                                margin-top: 12px;
                            }
                        </style>
                        <tbody id="product_order">
                            @if (count($all_product) > 0)
                            @foreach ($all_product as $item => $value )
                            <tr id="{{ $value ->product_id }}">
                                <td>{{ $value ->product_id }}</td>
                                <td><a href="{{ URL::to('/product/'.$value->product_slug) }}" target="_blank">{!!
                                        $value->product_name !!}</a></td>
                                <td>{{ $value ->category->category_name }} <span
                                        class="text-muted small">({{ $value->category_id }})</span></td>
                                <td>{{ $value ->brand->brand_name }} <span
                                        class="text-muted small">({{ $value->brand_id }})</span></td>
                                <td style="font-style: italic">{{ number_format($value ->product_price) }} đ</td>
                                <td style="font-style: italic">{{ number_format($value ->product_price_discount) }} đ
                                </td>
                                <td>{{ $value ->product_quantity }} <span
                                        class="text-muted small">({{ $value->product_sales_quantity }})</span></td>
                                <td>
                                    <img src="{{ $value ->product_image }}"
                                        alt="{{ $value ->product_name }}" style="width: 80px">
                                </td>
                                <td class="text-nowrap">
                                    @php
                                    $rat = $value->rating->avg('rating');
                                    echo round($rat);
                                    @endphp
                                    <i class="fas fa-star" style="color:#e7ab3c"></i>
                                </td>
                                <td><a target="_blank" href="{{ URL::to('/product-gallery/'.$value->product_id) }}"
                                        title="Thư viện ảnh"><button class="btn btn-outline-success"><i
                                                class="fas fa-images"></i></button></a></td>
                                <td><a target="_blank"
                                        href="{{ URL::to('/list-product-comment/'.$value->product_id) }}"><button
                                            class="btn btn-outline-secondary"><i
                                                class="fas fa-comments"></i></button></a></td>
                                <td><a target="_blank"
                                        href="{{ URL::to('/product-attribute/'.$value->product_id) }}"><button
                                            class="btn btn-outline-dark"><i class="fas fa-palette"></i></button></a>
                                </td>
                                @hasrole(['admin','author'])
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-outline-warning" name="delete_product"
                                        title="Xoá">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    &nbsp;
                                    <a href="{{ URL::to('/edit-product/'.$value ->product_id) }}" title="Sửa"><button
                                            type="button" class="btn btn-outline-info"><i
                                                class="fas fa-edit"></i></button></a>
                                </td>
                                @endhasrole
                                <td class="text-nowrap">
                                    <a href="#" data-status="{{ $value ->product_status }}"
                                        class="change_status_product">
                                        @php
                                        if ($value ->product_status==0){
                                        echo "Hiển thị";
                                        }
                                        else {
                                        echo "Ẩn";
                                        }
                                        @endphp
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                @hasrole(['admin','author'])
                <a href="{{ URL::to('/add-product') }}"><button class="btn btn-outline-info">Thêm sản
                        phẩm</button></a>
                <form action="{{url('ad/export-csv')}}" method="POST">
                    @csrf
                    <input type="submit" value="Export CSV" name="export_csv"
                        class="btn btn-sm btn-outline-success mt-2">
                </form>

                @endhasrole
                {!! $all_product->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>
@push('custom-scripts-ajax')
<script src="{{ URL::asset('/backend/js/all_product.js') }}"></script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.product').addClass('active');
        });
</script>
@endpush
@endsection