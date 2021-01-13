@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bình luận bài viết:
                        @isset($comment)
                        <span class="count_comment">{{ $comment->count() }}</span>
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
                                <th>Tên</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
                                <th>Bài viết</th>
                                <th>Thay đổi</th>
                            </tr>
                        </thead>
                        <tbody id="comment_data">
                            @isset($comment)
                            @foreach ($comment as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    {{ $item->customer->customer_name }}</td>
                                <td>
                                    <span class="d-inline-block text-truncate"
                                        style="max-width: 450px;">{{ $item->content }}</span>
                                </td>
                                <td class="text-nowrap">{{ $item->time }}</td>
                                <td>
                                    <a target="_blank" href="{{ URL::to('/blog/view/'.$item->post->post_slug) }}"
                                        title="Xem chi tiết"><span class="d-inline-block text-truncate"
                                            style="max-width: 150px;">({{ $item->id }}){{ $item->post->post_title }}</span></a>
                                </td>
                                <td class="text-nowrap">
                                    <button class="btn btn-outline-warning" name='delete_comment'
                                        data-cmt_id='{{ $item->id }}'><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>

                </div>

                <!-- /.card-body -->
            </div>
            <div class="card-tools">
                {!! $comment->render('vendor.pagination.name') !!}
            </div>

            <!-- /.card -->
        </div>
    </div>
</div>

@push('custom-scripts-ajax')
<script>
    $(function () {
    $("button[name = 'delete_comment']").click(function () {
        swal({
            title: "Bạn chắc chắn muốn xoá?",
            text: "Sản phẩm này sẽ biến mất vĩnh viển!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    var cmt_id = $(this).data('cmt_id');
                    let _token = $("input[name = '_token']").val();
                    var this_tr = $(this).parent().parent();
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "{{url('/delete-post-comment')}}",
                        data: { id: cmt_id, _token: _token },
                        dataType: "html",
                        success: function (data) {
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
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.post').addClass('active');
        });
</script>
@endpush
@endsection