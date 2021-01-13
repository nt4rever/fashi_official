@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mt-3">
            <h2>Thêm sản phẩm</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/save-product') }}" method="post" id="form_add_product"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                placeholder="louis vuitton">
                        </div>
                        <div class="form-group">
                            <label for="product_desc">Mô tả</label>
                            <textarea class="textarea" name="product_desc" id="product_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select class="form-control" id="category_id" name="category_id">
                                {!! $category_option !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Thương hiệu <span class="text-muted small">(nếu có)</span></label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                @if (count($brand))
                                @foreach ($brand as $item=>$value)
                                <option value="{{ $value->brand_id }}">{{ $value->brand_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_price">Giá</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control money" name="product_price" id="product_price">
                                <div class="input-group-append">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_price_discount">Giảm giá <span class="text-muted small">(nếu
                                    có)</span></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control money" name="product_price_discount"
                                    id="product_price_discount">
                                <div class="input-group-append">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_quantity">Số lượng</label>
                            <input type="number" class="form-control" name="product_quantity" id="product_quantity"
                                min="1">
                        </div>
                        <div class="form-group">
                            <label for="product_image">Ảnh sản phẩm</label>
                            <input type="file" class="form-control" name="product_image" id="product_image">
                        </div>
                        <div class="form-group">
                            <label for="product_desc">Mô tả chi tiết</label>
                            <textarea class="" name="product_content" id="product_content"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_tag">Tag</label>
                            <input type="text" data-role="tagsinput" name="product_tag">
                        </div>
                        <div class="form-group">
                            <label for="product_status">Trạng thái</label>
                            <select class="form-control" id="product_status" name="product_status">
                                <option value="0">Hiển thị</option>
                                <option value="1">Ẩn</option>
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
    CKEDITOR.replace( 'product_content', {
        filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',

    });
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.product').addClass('active');
        });
</script>
@endpush
@endsection