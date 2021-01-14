@extends('admin_layout')
@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-md-7 mt-3">
            <h2>Page contact</h2>
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    {{-- <h3 class="card-title">Quick Example</h3> --}}
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if ($contact)
                <form role="form" action="{{ URL::to('/page/contact-save') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Map iframe</label>
                            <textarea class="form-control" name="map_frame" id="" rows="3">
                              {{ $contact->map_frame }}
                          </textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ $contact->phone }}">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $contact->email }}">
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <textarea class="form-control" name="address" rows="3">
                                {!! $contact->address !!}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Social link</label>
                            <textarea class="form-control" name="social" rows="6">
                                {!! $contact->social !!}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Introduce</label>
                            <textarea class="form-control" name="introduce" rows="6">
                                {!! $contact->introduce !!}
                            </textarea>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                @else
                <form role="form" action="{{ URL::to('/page/contact-save') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Map iframe</label>
                            <textarea class="form-control" name="map_frame" id="" rows="3">

                          </textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" name="phone" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            <textarea class="form-control" name="address" id="" rows="3">

                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Social link</label>
                            <textarea class="form-control" name="social" rows="6">
                                <a href="https://www.facebook.com/levantanlop91"><i class="fa fa-facebook"></i></a>
                                <a href="https://www.facebook.com/levantanlop91"><i class="fa fa-instagram"></i></a>
                                <a href="https://www.facebook.com/levantanlop91"><i class="fa fa-twitter"></i></a>
                                <a href="https://www.facebook.com/levantanlop91"><i class="fa fa-pinterest"></i></a>
                            </textarea>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                @endif

            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@push('active-nav')
<script>
    $(function(){
            $('a.nav-link.contact').addClass('active');
        });
</script>
@endpush
@endsection