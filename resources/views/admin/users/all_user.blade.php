@extends('admin_layout')
@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tài khoản auth:
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
                                <th>Tên User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                {{-- <th>Password</th> --}}
                                <th>Admin</th>
                                <th>Author</th>
                                <th>User</th>
                                <th>Submit</th>
                            </tr>
                        </thead>
                        <tbody id="comment_data">
                            @isset($admin)
                            @foreach ($admin as $item)
                            <form action="{{ URL::to('/assign-roles') }}" method="POST">
                                @csrf
                                {{-- @if ($item->admin_id==1)
                                <tr>
                                    <td>{{ $item->admin_id }}</td>
                                    <td>
                                        {{ $item->admin_name }}</td>
                                    <td>
                                        {{ $item->admin_email }}
                                    </td>
                                    <input type="hidden" name="admin_email" value="{{ $item->admin_email }}">
                                    <td>{{ $item->admin_phone }}</td>
                                    <td>{{ $item->admin_password }}</td>
                                    <td><input type="checkbox" name="admin_role" disabled
                                            {{ $item->hasRole('admin') ? 'checked':'' }}></td>
                                    <td><input type="checkbox" name="author_role" disabled
                                            {{ $item->hasRole('author') ? 'checked':'' }}></td>
                                    <td><input type="checkbox" name="user_role" disabled
                                            {{ $item->hasRole('user') ? 'checked':'' }}>
                                    </td>
                                    <td><input type="submit" value="Gán quyền" disabled class="btn btn-sm btn-warning">
                                    </td>
                                </tr>
                                @else --}}
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        {{ $item->name }}</td>
                                    <td>
                                        {{ $item->email }}
                                    </td>
                                    <input type="hidden" name="admin_email" value="{{ $item->email }}">
                                    <input type="hidden" name="admin_id" value="{{ $item->id }}">
                                    <td>{{ $item->phone }}</td>
                                    {{-- <td>{{ $item->password }}</td> --}}
                                    <td><input type="checkbox" name="admin_role"
                                            {{ $item->hasRole('admin') ? 'checked':'' }}></td>
                                    <td><input type="checkbox" name="author_role"
                                            {{ $item->hasRole('author') ? 'checked':'' }}></td>
                                    <td><input type="checkbox" name="user_role"
                                            {{ $item->hasRole('user') ? 'checked':'' }}>
                                    </td>
                                    <td><input type="submit" value="Gán quyền" class="btn btn-sm btn-warning">
                                        <a href="{{ URL::to('/delete-user-roles/'.$item->id) }}" onclick="return confirm('Xoa muc nay!')"
                                            class="btn btn-danger btn-sm">Xoá</a>
                                    </td>
                                </tr>
                                {{-- @endif --}}

                            </form>
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
                <a href="{{ URL::to('/register-auth') }}"><Button class="btn btn-outline-info">Thêm User</Button></a>
                {!! $admin->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>

@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.auth').addClass('active');
        });
</script>
@endpush
@endsection