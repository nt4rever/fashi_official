@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Thêm user auth</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}

                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/register-auth-handle') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="admin_name">Tên user</label>
                            <input type="text" class="form-control" id="admin_name" name="admin_name" required
                                value="{{ old('admin_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="admin_email">Email</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" required
                                value="{{ old('admin_email') }}">
                        </div>
                        <div class="form-group">
                            <label for="admin_phone">Phone</label>
                            <input type="text" class="form-control" id="admin_phone" name="admin_phone" required
                                value="{{ old('admin_phone') }}">
                        </div>
                        <div class="form-group">
                            <label for="admin_password">Password</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="admin_repeat_password">Repeat password</label>
                            <input type="password" class="form-control" id="admin_repeat_password"
                                name="admin_repeat_password" required>
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
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
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.auth').addClass('active');
        });
</script>
@endpush
@endsection