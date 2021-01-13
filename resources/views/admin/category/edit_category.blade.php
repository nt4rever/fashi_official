@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Sửa danh mục</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/save-edit-category/'.$category_edit->category_id) }}"
                    method="post" id="form_add_category" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_name">Tên danh mục</label>
                            <input type="text" class="form-control" id="category_name" name="category_name"
                                placeholder="louis vuitton" value="{{ $category_edit->category_name }}">
                        </div>
                        <div class="form-group">
                            <label for="category_desc">Mô tả</label>
                            <textarea class="textarea" name="category_desc" id="category_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                            {{ $category_edit->category_desc }}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="category_image">Ảnh danh mục</label>
                            <input type="file" class="form-control" name="category_image" id="category_image">
                            @isset($category_edit->category_image)
                            <img src="{{ URL::asset('public/uploads/category/'.$category_edit->category_image) }}"
                                class="mt-3" alt="" style="width: 150px">
                            @endisset
                        </div>
                        <div class="form-group">
                            <label for="category_status">Trạng thái</label>
                            <select class="form-control" id="category_status" name="category_status">
                                @php
                                if ($category_edit->category_status == 0) {
                                @endphp
                                <option value="0" selected>Hiển thị</option>
                                <option value="1">Ẩn</option>
                                @php
                                }
                                else {
                                @endphp
                                <option value="0">Hiển thị</option>
                                <option value="1" selected>Ẩn</option>
                                @php
                                }
                                @endphp
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category_status">Danh mục cha</label>
                            <select class="form-control" id="category_parentId" name="category_parentId">
                                <option value="0">Chọn danh mục cha</option>
                                {{-- {!! $htmlOption !!} --}}
                                @isset($category)
                                @foreach ($category as $item)
                                <option value="{{ $item->category_id }}"
                                    {{ $item->category_id==$category_edit->category_parentId?"selected":"" }}>
                                    {{ $item->category_name }}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
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
    $(document).ready(function(){
        $('.textarea').summernote()
    });
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.category').addClass('active');
       
        });
</script>
@endpush
@endsection