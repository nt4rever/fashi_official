@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thương hiệu sản phẩm</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        <form action="{{ URL::to('/search-brand') }}" method="POST">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                @csrf
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
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên thương hiệu</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_brand as $item => $value)
                            <tr>
                                <td>{{ $value->brand_id }}</td>
                                <td><a href="{{ URL::to('/list-brand-detail/'.$value->brand_id) }}" target="_blank"
                                        rel="noopener noreferrer">{{ $value->brand_name }}</a></td>
                                <td>{!! $value->brand_desc !!}</td>
                                <td>
                                    @php
                                    if ($value->brand_status==0){
                                    @endphp
                                    <a href="{{ URL::to('/inactive-brand/'.$value->brand_id) }}"
                                        title="Click để ẩn">Hiển thị</a>
                                    @php
                                    }
                                    else {
                                    @endphp
                                    <a href="{{ URL::to('/active-brand/'.$value->brand_id) }}"
                                        title="Click để hiển thị">Ẩn</a>
                                    @php
                                    }
                                    @endphp
                                </td>
                                @hasrole(['admin','author'])
                                <td>
                                    @if ($value->brand_id==0)
                                    <button type="button" class="btn btn-outline-warning" name="delete_brand"
                                        title="Xoá" data-brandid="{{ $value->brand_id }}" disabled>
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    &nbsp;

                                    <button type="button" class="btn btn-outline-info" disabled><i
                                            class="fas fa-edit"></i></button>
                                    @else
                                    <button type="button" class="btn btn-outline-warning" name="delete_brand"
                                        title="Xoá" data-brandid="{{ $value->brand_id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    &nbsp;

                                    <a href="{{ URL::to('/edit-brand/'.$value->brand_id) }}" title="Sửa"><button
                                            type="button" class="btn btn-outline-info"><i
                                                class="fas fa-edit"></i></button></a>
                                    @endif

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
                <a href="{{ URL::to('/add-brand') }}"><button class="btn btn-outline-info">Thêm thương hiệu</button></a>
                @endhasrole
                {!! $all_brand->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

</div>
@push('custom-scripts-ajax')
<script src="{{ URL::asset('/backend/js/all_brand.js') }}"></script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.brand').addClass('active');
        });
</script>
@endpush
@endsection