@extends('admin_layout')
@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Question customer:
                        @isset($$ques)
                        <span class="count_comment">{{ $$ques->count() }}</span>
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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Question</th>
                                @hasrole(['admin','author'])
                                <th>Thay đổi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody id="comment_data">
                            @isset($ques)
                            @foreach ($ques as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->question }}</td>
                                @hasrole(['admin','author'])
                                <td class="text-nowrap">
                                    <button class="btn btn-outline-warning delete-ques" data-id="{{ $item->id }}"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                                @endhasrole
                            </tr>
                            @endforeach
                            @else
                            @push('custom-scripts')
                            <script>
                                swal('Chưa tồn tại câu hỏi nào!');
                            </script>
                            @endpush
                            @endisset
                        </tbody>
                    </table>

                </div>


                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                {!! $ques->render('vendor.pagination.name') !!}
            </div>
            <!-- /.card -->
            @hasrole(['admin','author'])
            <a href="{{ URL::to('/ad/add-faq') }}"><button class="btn btn-outline-info">Thêm câu hỏi thường
                    gặp</button></a>
            @endhasrole
        </div>
    </div>
</div>
@hasrole(['admin','author'])
@push('custom-scripts-ajax')
<script>
    $(function(){
            $('.delete-ques').click(function(){
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
                                    url: "{{url('/ad/delete-question')}}",
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
            $('a.nav-link.faq').addClass('active');
        });
</script>
@endpush
@endsection