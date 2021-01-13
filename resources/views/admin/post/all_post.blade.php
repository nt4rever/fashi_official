@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liệt kê bài viết, tin tức</h3>
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
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên bài viết</th>
                                <th>Mô tả</th>
                                <th>Ảnh</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_post as $item => $value)
                            <tr>
                                <td>{{ $value->post_id }}</td>
                                <td>{{ $value->post_title }}</td>
                                <td>
                                    <div style="
                                    width: 210px;
                                    height: 150px;
                                    overflow: scroll;">
                                        {!! $value->post_desc !!}
                                    </div>
                                </td>
                                <td><img src="{{ URL::to('uploads/post/'.$value->post_image) }}" alt=""
                                        style="width: 80px"></td>
                                <td>{{ $value->category->category_post_name }}</td>
                                <td class="text-nowrap">{{ $value->post_status==0?'Hiển thị' : 'Ẩn' }}</td>
                                @hasrole(['admin','author'])
                                <td class="text-nowrap">
                                    <a href="{{ URL::to('/edit-post/'.$value->post_id) }}"
                                        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a onclick="return confirm('Bạn có chắc muốn xoá!')"
                                        href="{{ URL::to('/delete-post/'.$value->post_id) }}"
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
                <a href="{{ URL::to('/add-post') }}"><button class="btn btn-outline-info">Thêm bài viết</button></a>
                @endhasrole
                {!! $all_post->render('vendor.pagination.name') !!}
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