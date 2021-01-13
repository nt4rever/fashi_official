@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Thêm deal</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">

                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/ad/save-deal') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="home_slider_image">Chọn sản phẩm</label></label>
                            <select name="product_id" class="form-control choose_product">
                                <option value="" disabled selected>Chọn sản phẩm</option>
                                @foreach ($product as $item)
                                @if ($item->category->category_status==0)
                                <option value="{{ $item->product_id }}">{{ $item->product_name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deal_desc">Mô tả</label>
                            <textarea class="textarea" name="deal_desc" id="deal_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                {!! "<h2>Deal Of The Week</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed<br /> do ipsum dolor sit amet,
                                    consectetur adipisicing elit </p>" !!}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" class="form-control money price" name="price" readonly value="">
                        </div>
                        <div class="form-group">
                            <label for="price_discount">Price discount</label>
                            <input type="text" class="form-control money price_discount" name="price_discount" value=""
                                required>
                        </div>
                        <div class="form-group">
                            <label>Date:</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#reservationdate" name="deal_time" />
                                <div class="input-group-append" data-target="#reservationdate"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
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
        $('.textarea').summernote();
        $('#reservationdate').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
        $(document).on('change','.choose_product', function(){
            var product_id = $(this).val();
            var _token = $('input[name=_token]').val();
            $.ajax({
                type: "POST",
                cache: false,
                url: "{{ url('/ad/get-product') }}",
                data: { product_id: product_id, _token:_token },
                dataType: "json",
                success: function (datajson) {
                    $('.price').val(datajson.product_price);
                    $('.price_discount').val(datajson.product_price_discount);
                },
                error: function (error) {
                    swal("Erorr!");
                }
            });
        });
    });

</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.deal').addClass('active');
        });
</script>
@endpush
@endsection