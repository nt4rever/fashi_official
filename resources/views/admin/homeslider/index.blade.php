@extends('admin_layout')
@section('admin_content')

<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Home Slider</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Desc</th>
                                <th>Sale</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($home_slider as $item)
                            <tr>
                                <td><img src="{{ URL::asset('uploads/homeslider/'.$item->home_slider_image) }}" alt="" style="width: 200px;">
                                </td>
                                <td>{!! $item->home_slider_desc !!}</td>
                                <td>{!! $item->home_slider_sale !!}</td>
                                @hasrole(['admin','author'])
                                <td>
                                    <a href="{{ URL::to('/ad/edit-home-slider/'.$item->home_slider_id) }}" title="Xoá">
                                        <button type="button" class="btn btn-outline-info">
                                            <i class="fas fa-edit"></i></button></a>
                                    </button>
                                    <a href="{{ URL::to('/ad/delete-home-slider/'.$item->home_slider_id) }}"
                                        onclick="return confirm('Bạn chắc chắn muốn xoá!')" title="Xoá">
                                        <button type="button" class="btn btn-outline-warning">
                                            <i class="fas fa-trash-alt"></i></button></a>
                                    </button>
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
                <a href="{{ URL::to('/ad/add-home-slider') }}"><button class="btn btn-outline-info">Thêm home
                        slide</button></a>
                @endhasrole
                {!! $home_slider->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

</div>
@push('custom-scripts-ajax')

@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.homeslider').addClass('active');
        });
</script>
@endpush
@endsection