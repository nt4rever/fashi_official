@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh mục bài viết, tin tức</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($category_post as $item => $value)
                            <tr>
                                <td>{{ $value->category_post_id }}</td>
                                <td>{{ $value->category_post_name }}</td>
                                <td>{!! $value->category_post_desc !!}</td>
                                <td>
                                    @php
                                    if ($value->category_post_status==0){
                                    @endphp
                                    <a href="{{ URL::to('/change-status-category-post/'.$value->category_post_id) }}"
                                        title="Click để ẩn">Hiển thị</a>
                                    @php
                                    }
                                    else {
                                    @endphp
                                    <a href="{{ URL::to('/change-status-category-post/'.$value->category_post_id) }}"
                                        title="Click để hiển thị">Ẩn</a>
                                    @php
                                    }
                                    @endphp
                                </td>
                                @hasrole(['admin','author'])
                                <td>
                                    <a href="{{ URL::to('/edit-category-post/'.$value->category_post_id) }}"
                                        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a onclick="return confirm('Bạn có chắc muốn xoá!')"
                                        href="{{ URL::to('/delete-category-post/'.$value->category_post_id) }}"
                                        class="btn btn-danger btn-sm" title="Xoá"><i class="fas fa-trash"></i></a>

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
                <a href="{{ URL::to('/add-category-post') }}"><button class="btn btn-outline-info">Thêm danh
                        mục</button></a>
                @endhasrole
                {!! $category_post->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

</div>
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.postt').addClass('active');
        });
</script>
@endpush
@endsection