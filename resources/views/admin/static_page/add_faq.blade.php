@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Thêm câu hỏi thường gặp</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ URL::to('/ad/save-faq') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="faq_question">Câu hỏi</label>
                            <input type="text" class="form-control" id="faq_question" name="faq_question"
                                placeholder="faq?" required>
                        </div>
                        <div class="form-group">
                            <label for="faq_answer">Trả lời</label>
                            <textarea class="" name="faq_answer" id="faq_answers" placeholder="Place some text here"
                                style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                required></textarea>
                        </div>

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
@push('custom-scripts')
<script>
    $(document).ready(function(){
        $('.textarea').summernote()
    });
    CKEDITOR.replace( 'faq_answer', {
        filebrowserImageBrowseUrl: "{{ url('file-browser?_token=' . csrf_token()) }}",
        filebrowserImageUploadUrl: "{{ url('uploads-ckeditor?_token=' . csrf_token()) }}",
        filebrowserUploadMethod: "form"
    });
</script>
@endpush
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.faq').addClass('active');
        });
</script>
@endpush
@endsection