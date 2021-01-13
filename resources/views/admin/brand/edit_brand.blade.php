@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Sửa thương hiệu</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/save-edit-brand/'.$brand_edit->brand_id) }}" method="post" id="form_add_brand">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="brand_name">Tên thương hiệu</label>
                            <input type="text" class="form-control" id="brand_name" name="brand_name"
                                placeholder="louis vuitton" value="{{ $brand_edit->brand_name }}">
                        </div>
                        <div class="form-group">
                            <label for="brand_desc">Mô tả</label>
                            <textarea class="textarea" name="brand_desc" id="brand_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                            {{ $brand_edit->brand_desc }}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="brand_status">Trạng thái</label>
                            <select class="form-control" id="brand_status" name="brand_status">
                                @php
                                if ($brand_edit->brand_status == 0) {
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
            $('a.nav-link.brand').addClass('active');
        });
</script>
@endpush
@endsection