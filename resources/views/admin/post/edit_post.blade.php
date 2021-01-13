@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-9 mt-3">
            <h2>Sửa bài viết</h2>
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
                <form role="form" action="{{ URL::to('/save-edit-post/'.$post->post_id) }}" method="post"
                    id="form_add_post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="post_title">Tên bài viết</label>
                            <input type="text" class="form-control" id="slug" name="post_title"
                                onkeyup="ChangeToSlug();" placeholder="louis vuitton" required
                                value="{{ $post->post_title }}">
                        </div>
                        <div class="form-group">
                            <label for="post_slug">Slug</label>
                            <input type="text" class="form-control" id="convert_slug" name="post_slug"
                                placeholder="slug" required readonly="true" value="{{ $post->post_slug }}">
                        </div>
                        <div class="form-group">
                            <label for="">Danh mục</label>
                            <select name="category_post_id" id="category_post_id" class="form-control">
                                @foreach ($category_post as $item)
                                @if ($item->category_post_id == $post->category_post_id)
                                <option value="{{ $item->category_post_id }}" selected>{{ $item->category_post_name }}
                                </option>
                                @else
                                <option value="{{ $item->category_post_id }}">{{ $item->category_post_name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="post_image">Ảnh bài viết</label>
                            <input type="file" class="form-control" name="post_image" id="post_image">
                            <img src="{{ URL::to('uploads/post/'.$post->post_image) }}" alt="" style="width: 150px"
                                class="mt-2">
                        </div>
                        <div class="form-group">
                            <label for="post_desc">Tóm tắt bài viết</label>
                            <textarea class="textarea" name="post_desc" id="post_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                required>{{ $post->post_desc }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="post_content">Chi tiết</label>
                            <textarea class="" name="post_content" id="product_content"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{  $post->post_content }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="post_meta_keyword">Meta từ khoá</label>
                            <textarea class="form-control" name="post_meta_keyword" id="post_meta_keyword"
                                placeholder="Place some text here"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                required>{{   $post->post_meta_keyword }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="post_meta_desc">Meta nội dung</label>
                            <textarea class="form-control" name="post_meta_desc" id="post_meta_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 80px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                required>{{ $post->post_meta_desc }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="post_status">Trạng thái</label>
                            <select class="form-control" id="post_status" name="post_status">
                                <option value="0" {{ $post->post_status==0 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="1" {{ $post->post_status==1 ? 'selected' : '' }}>Ẩn</option>
                            </select>
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
    CKEDITOR.replace( 'product_content', {
        filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',

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