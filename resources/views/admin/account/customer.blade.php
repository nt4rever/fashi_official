@extends('admin_layout')
@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tài khoản khách hàng:
                        @isset($all_account)
                        <span class="count_comment">{{ $all_account->count() }}</span>
                        @endisset</h3>
                    <span style="color: red">
                        @php
                        $message = Session::get('message');
                        if ($message){
                        echo "<br>".$message;
                        Session::put('message', null);
                        }
                        @endphp</span>

                    <div class="card-tools">
                        <form action="">
                            @csrf
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Create at</th>
                                <th>Update at</th>
                                <th>Thay đổi</th>
                            </tr>
                        </thead>
                        <tbody id="comment_data">
                            @isset($all_account)
                            @foreach ($all_account as $item)
                            <tr>
                                <td>{{ $item->customer_id }}</td>
                                <td>
                                    {{ $item->customer_name }}</td>
                                <td>
                                    {{ $item->customer_email }}
                                </td>
                                <td>{{ $item->customer_phone }}</td>
                                <td>{{ $item->customer_address }}</td>
                                <td id="cus{{ $item->customer_id }}">
                                    @if ($item->customer_status==0)
                                    <span class="badge badge-success">Hoạt động</span>
                                    @else
                                    <span class="badge badge-secondary">Khoá</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td id="btn{{ $item->customer_id }}">
                                    <button class="btn btn-outline-warning change-password" title="Đổi mật khẩu"
                                        data-id="{{ $item->customer_id }}"><i class="fas fa-key"></i></button>
                                    @if ($item->customer_status==0)
                                    <button class="btn btn-outline-danger block-account" title="Khoá tài khoản"
                                        data-id="{{ $item->customer_id }}"><i class="fas fa-ban"></i></button>
                                    @else
                                    <button class="btn btn-outline-info block-account" title="Khoá tài khoản"
                                        data-id="{{ $item->customer_id }}"><i class="fas fa-unlock"></i></button>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                            @else
                            @push('custom-scripts')
                            <script>
                                swal('Chưa tồn tại tài khoản nào!');
                            </script>
                            @endpush
                            @endisset
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                {!! $all_account->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>
<div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form action="">
                        @csrf
                    </form>
                    <label for="">Mật khẩu mới</label>
                    <input type="hidden" name="customer_id">
                    <input type="password" name="new_password" id="" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save-change-password">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@push('custom-scripts-ajax')
<script>
    $(function(){
                $(document).on('click','.change-password',function(){
                var id = $(this).data('id');
                $('input[name=customer_id]').val(id);
                $('#modal-sm').modal('show');
            });
            $(document).on('click','.save-change-password',function(){
                var _token = $('input[name=_token]').val();
                var id =  $('input[name=customer_id]').val();
                var password = $('input[name=new_password]').val();
                $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/admin-account-customer-change-password')}}",
                        data: { id: id, password: password, _token: _token },
                        dataType: "html",
                        success: function (data) {
                            console.log(data); 
                            toastr.success('Change password success!')
                        }
                        ,
                        error: function (error) {
                            swal("Erorr!");
                        }
                    });
                $('input[name=new_password]').val('');
                $('#modal-sm').modal('hide');
            });
            $(document).on('click','.block-account', function(){
            
                var id = $(this).data('id');
                var _token = $('input[name=_token]').val();
                swal({
                    title: "Khoá/mở tài khoản này?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/admin-account-customer-block')}}",
                        data: { id: id, _token: _token },
                        dataType: "json",
                        success: function (data) {
                            $('#cus'+id).html(data.status);
                            $('#btn'+id).html(data.button);
                            toastr.success('Khoá tài khoản thành công!')
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
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.customer').addClass('active');
        });
</script>
@endpush
@endsection