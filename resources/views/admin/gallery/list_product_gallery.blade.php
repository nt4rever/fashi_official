@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thư viện ảnh</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        {{-- <form action="{{ URL::to('/search-category') }}" method="POST">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            @csrf
                            <input type="text" class="form-control float-right" placeholder="Search" name="keyword"
                                required>

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        </form> --}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Ảnh</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody id="gallery_data">
                            @isset($list)
                            @foreach ($list as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td contenteditable='true' class='edit_gallery_name' data-gal_id='{{ $item->id }}'>
                                    {{ $item->name }}</td>
                                <td><img src="{{$item->path }}" alt=""
                                        style="width: 150px"></td>
                                @hasrole(['admin','author'])
                                <td><button class="btn btn-outline-warning" name='delete_gallery'
                                        data-gal_id='{{ $item->id }}'><i class="fas fa-trash-alt"></i></button>
                                </td>
                                @endhasrole
                            </tr>
                            @endforeach
                            @else
                            @push('custom-scripts')
                            <script>
                                swal('Không có ảnh nào trong thư viện!');
                            </script>
                            @endpush
                            @endisset
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                {{-- <button class="btn btn-outline-primary" id="reload_gallery"
                    data-product_id="{{ $product_id }}">Reload</button> --}}
                @hasrole(['admin','author'])
                <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#modal-lg">Thêm ảnh</button>
                @endhasrole
                {{-- {!! $all_category->render('vendor.pagination.name') !!} --}}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>
<!-- /.modal -->
@hasrole(['admin','author'])
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm ảnh</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/insert-product-gallery/'.$product_id) }}" method="POST" enctype="multipart/form-data"
                id="form_gallery">
                @csrf
                <div class="modal-body">
                    <span id="error_gallery" class="badge badge-warning"></span>
                    <input type="file" class="from-control" name="file[]" accept="image/*" multiple id="file">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@push('custom-scripts-ajax')
<script>
    $(function () {
    $('#form_gallery').change(function () {
        var error = "";
        var files = $('#file')[0].files;
        if (files.length > 5) {
            error = "Tối đa chỉ 5 ảnh";
        } else if (files.length = '') {
            error = "Không được bỏ trống ảnh";
        } else if (files.size > 2000000) {
            error = "Kích thước ảnh quá 2MB";
        }
        if (error == "") {
            $('#error_gallery').html(error);
        } else {
            $('#file').val('');
            $('#error_gallery').html(error);
        }
    });

    // $(document).on('blur', '.edit_gallery_name', function () {
    //     var gal_id = $(this).data('gal_id');
    //     var gal_text = $(this).text();
    //     let _token = $('meta[name="csrf-token"]').attr('content');
    //     $.ajax({
    //         type: "POST",
    //         cache: false,
    //         url: "{{url('/update-product-gallery-name')}}",
    //         data: { gallery_id: gal_id, gallery_text: gal_text, _token: _token },
    //         dataType: "html",
    //         success: function (data) {
    //             console.log(data)
    //         }
    //         ,
    //         error: function (error) {
    //             swal("Erorr!");
    //         }
    //     });
    // });

    $("button[name = 'delete_gallery']").click(function () {
        swal({
            title: "Bạn chắc chắn muốn xoá?",
            text: "Sản phẩm này sẽ biến mất vĩnh viển!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var gal_id = $(this).data('gal_id');
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    var this_tr = $(this).parent().parent();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/delete-product-gallery')}}",
                        data: { gallery_id: gal_id, _token: _token },
                        dataType: "html",
                        success: function (data) {
                            console.log(data);
                            this_tr.remove();
                        }
                        ,
                        error: function (error) {
                            swal("Erorr!");
                        }
                    });
                }
            });
    });
});
</script>
@endpush
@endhasrole
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.product').addClass('active');
        });
</script>
@endpush
@endsection