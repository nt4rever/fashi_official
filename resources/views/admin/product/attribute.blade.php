@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thuộc tính sản phẩm: <span
                            class="text-bold text-muted">{{ $product->product_name }}</span></h3>
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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Extra Price</th>
                                @hasrole(['admin','author'])
                                <th>Change</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attribute as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->size }}</td>
                                <td>{{ $item->color }}</td>
                                <td>{{ $item->extra_price }}</td>
                                @hasrole(['admin','author'])
                                <td><a href="#" class="delete-attribute" data-id="{{ $item->id }}"><button
                                            class="btn btn-outline-warning"><i class="fas fa-trash"></i></button></a>
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
                <button class="btn btn-outline-info add-attribute">Thêm thuộc tính</button>
                @endhasrole
            </div>

            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
    @hasrole(['admin','author'])
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <form action="{{ URL::to('/add-product-attribute/'.$product->product_id) }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm thuộc tính</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="modal_product_content">
                            <div class="form-group">
                                <label for="product_quantity">Size</label>
                                <input type="text" class="form-control" name="size" id="size" required>
                            </div>
                            <div class="form-group">
                                <label for="product_quantity">Color</label>
                                <input type="text" class="form-control" name="color" id="color" required>
                            </div>
                            <div class="form-group">
                                <label for="product_quantity">Extra price</label>
                                <input type="number" class="form-control" name="extra_price" id="extra_price" min="0"
                                    value="0" required>
                            </div>
                        </p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-warning">Lưu</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @endhasrole
</div>
@hasrole(['admin','author'])
@push('custom-scripts-ajax')
<script>
    $(function(){
        $('.add-attribute').click(function(){
            $('#modal-lg').modal('show');
        });

        $('a.delete-attribute').click(function(){
           swal({
            title: "Bạn chắc chắn muốn xoá?",
            text: "Mục này sẽ biến mất vĩnh viển!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var id = $(this).data('id');
                    var _token = $('input[name="_token"]').val();
                    var this_tr = $(this).parent().parent();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/delete-product-attribute')}}",
                        data: { id: id, _token: _token },
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
            return false;
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