@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Thêm home slider</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">

                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/ad/save-edit-home-slider/'.$home_slider->home_slider_id) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="home_slider_image">Ảnh silde</label></label>
                            <input type="file" class="form-control" name="home_slider_image">
                        </div>
                        <img src="{{ $home_slider->home_slider_image }}" alt=""
                            style="width: 200px;">
                        <div class="form-group">
                            <label for="brand_desc">Mô tả</label>
                            <textarea class="textarea" name="home_slider_desc" id="home_slider_desc"
                                placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                {!! $home_slider->home_slider_desc !!}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="home_slider_sale">Sales</label>
                            <textarea class="form-control"
                                name="home_slider_sale">{!! $home_slider->home_slider_sale !!}</textarea>
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
        $('.textarea').summernote()
    });

</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.homeslider').addClass('active');
        });
</script>
@endpush
@endsection