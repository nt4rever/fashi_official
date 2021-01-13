@extends('admin_layout')

@section('admin_content')
<!-- /.row -->
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bình luận:
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
                        <h5>{{ $product->product_name }}</h5>
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
                                <th>Nội dung</th>
                                <th>Trả lời</th>
                                <th>Thời gian</th>
                                <th>Thay đổi</th>
                            </tr>
                        </thead>
                        <tbody id="comment_data">
                            @isset($comment)
                            @foreach ($comment as $item)
                            <tr>
                                <td>{{ $item->comment_id }}</td>
                                <td>
                                    {{ $item->customer->customer_name }}</td>
                                <td>
                                    {{ $item->comment_content }}
                                </td>
                                <td>
                                    @if ($item->reply_id==0)
                                    <form action="{{ url('/ad/add-admin-comment') }}" method="post">
                                        @csrf
                                        <div class="form-inline">
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                            <input type="hidden" name="reply_id" value="{{ $item->comment_id }}">
                                            <input type="text" class="form-control" placeholder="reply" name="content"
                                                autocomplete="off" required>
                                            <button class="btn btn-secondary ml-1"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </form>
                                    @else
                                    {{ $item->reply_id }}
                                    @endif
                                </td>
                                <td>{{ $item->comment_time }}</td>
                                <td><a href="{{ URL::to('/product/'.$item->product->product_slug) }}" title="Chi tiết"
                                        target="_blank" rel="noopener noreferrer" class="btn btn-outline-info"><i
                                            class="fas fa-eye"></i></a>
                                    <button class="btn btn-outline-warning" name='delete_comment'
                                        data-cmt_id='{{ $item->comment_id }}'><i class="fas fa-trash-alt"></i></button>
                                </td>
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
                        url: "{{url('/delete-product-comment')}}",
                        data: { comment_id: cmt_id, _token: _token },
                        dataType: "html",
                        success: function (data) {
                            console.log(data);
                            var count = $('.count_comment').text();
                            count--;
                            $('.count_comment').text(count);
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
            $('a.nav-link.comment').addClass('active');
        });
</script>
@endpush
@endsection