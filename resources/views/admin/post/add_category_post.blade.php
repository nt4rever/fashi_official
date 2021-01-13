@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Thêm danh mục bài viết</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
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
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/save-category-post') }}" method="post"
                    id="form_add_category_post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_post_name">Tên danh mục bài viết</label>
                            <input type="text" class="form-control" id="slug" name="category_post_name"
                                onkeyup="ChangeToSlug();" placeholder="louis vuitton" required>
                        </div>
                        <div class="form-group">
                            <label for="category_post_desc">Mô tả</label>
                            <textarea class="textarea" name="category_post_desc" id="category_post_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category_post_status">Trạng thái</label>
                            <select class="form-control" id="category_post_status" name="category_post_status">
                                <option value="0">Hiển thị</option>
                                <option value="1">Ẩn</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category_post_slug">Slug</label>
                            <input type="text" class="form-control" id="convert_slug" name="category_post_slug"
                                placeholder="slug" required readonly="true">
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class=" card-footer">
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
    $(document).ready(function(){
        $('.textarea').summernote()
    });
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.postt').addClass('active');
        });
</script>
@endpush
@endsection