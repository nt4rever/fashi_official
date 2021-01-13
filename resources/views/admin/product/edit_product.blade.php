@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mt-3">
            <h2>Sửa sản phẩm</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/save-edit-product/'.$product->product_id) }}" method="post"
                    id="form_edit_product" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="product_name">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                placeholder="louis vuitton" value="{{ $product->product_name }}">
                        </div>
                        <div class="form-group">
                            <label for="product_desc">Mô tả</label>
                            <textarea class="textarea" name="product_desc" id="product_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                             {{ $product->product_desc }}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select class="form-control" id="category_id" name="category_id">
                                {!! $htmlOption !!}
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Thương hiệu <span class="text-muted small">(nếu có)</span></label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                @foreach ($brand as $item=> $value)
                                @if ($value->brand_id==$product->brand_id)
                                <option value="{{ $value->brand_id }}" selected>{{ $value->brand_name }}</option>
                                @else
                                <option value="{{ $value->brand_id }}">{{ $value->brand_name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_price">Giá</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control money" name="product_price" id="product_price"
                                    value="{{ $product->product_price }}">
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
                                    id="product_price_discount" value="{{ $product->product_price_discount }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">đ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="product_quantity">Số lượng</label>
                            <input type="number" class="form-control" name="product_quantity" id="product_quantity"
                                value="{{ $product->product_quantity }}">
                        </div>
                        <div class="form-group">
                            <label for="product_image">Ảnh sản phẩm</label>
                            <input type="file" class="form-control" name="product_image" id="product_image">
                            <figure class="mt-2">
                                <img src="{{ URL::asset('/uploads/product/'.$product->product_image) }}" alt=""
                                    style="width: 150px">
                            </figure>
                        </div>

                        <div class="form-group">
                            <label for="product_desc">Mô tả chi tiết</label>
                            <textarea class="" name="product_content" id="product_content"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                            {{ $product->product_content }}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="product_tag">Tag</label>
                            <input type="text" data-role="tagsinput" name="product_tag"
                                value="{{ $product->product_tag }}">
                        </div>
                        <div class="form-group">
                            <label for="product_status">Trạng thái</label>
                            <select class="form-control" id="product_status" name="product_status">
                                @if ($product->product_status==0)
                                <option value="0" selected>Hiển thị</option>
                                <option value="1">Ẩn</option>
                                @else
                                <option value="0">Hiển thị</option>
                                <option value="1" selected>Ẩn</option>
                                @endif
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