@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="#"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <span>{{ __('User - Information') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->


<!-- Contact Section Begin -->
<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                @if(Session::get('message'))
                <div class="alert alert-danger" role="alert">
                    @php
                    echo Session::get('message');
                    Session::put('message', null);
                    @endphp
                </div>
                @endif
                <div class="contact-title">
                    <h4>{{ __('Your account') }}</h4>
                </div>
                <div class="contact-widget">
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-text"></i>
                        </div>
                        <div class="ci-text">
                            <span>{{ __('Name') }}:</span>
                            <p>{{ $user->customer_name }}</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-location-pin"></i>
                        </div>
                        <div class="ci-text">
                            <span>{{ __('Address') }}:</span>
                            @isset($user->customer_address)
                            <p>{{ $user->customer_address }}</p>
                            @else
                            <p>null</p>
                            @endisset
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="ci-text">
                            <span>{{ __('Phone') }}:</span>
                            @isset($user->customer_phone)
                            <p>{{ $user->customer_phone }}</p>
                            @else
                            <p>null</p>
                            @endisset
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-email"></i>
                        </div>
                        <div class="ci-text">
                            <span>Email:</span>
                            <p>{{ $user->customer_email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="contact-form">
                    <div class="leave-comment">
                        <h4>Hi {{ $user->customer_name }}</h4>
                        <p>Avatar</p>
                        <div>
                            @isset($user->customer_image)
                            <img src="{{ $user->customer_image }}" alt="" style='max-width: 250px;
                            margin-bottom: 20px;'>
                            @else
                            <p>{{ __('You need update avatar') }}!</p>
                            @endisset
                        </div>
                        <button class="site-btn" data-toggle="modal" data-target="#modal-update">{{ __('Update') }}</button>
                        <button class="site-btn" data-toggle="modal" data-target="#modal-password">{{ __('Change Password') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->
<div class="modal fade" id="modal-update">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ URL::to('/u/save-change') }}" method="post" enctype="multipart/form-data"
                id="form-update-info">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Update your information') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="customer_name">{{ __('Name') }}:</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                            value="{{ $user->customer_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_address">{{ __('Address') }}:</label>
                        <input type="text" name="customer_address" id="customer_address" class="form-control"
                            value="{{ $user->customer_address }}" required placeholder="Your address">
                    </div>
                    <div class="form-group">
                        <label for="customer_phone">{{ __('Phone') }}:</label>
                        <input type="number" name="customer_phone" id="customer_phone" class="form-control"
                            value="{{ $user->customer_phone }}" required placeholder="09xxxxxxx">
                    </div>
                    <div class="form-group">
                        <label for="customer_email">Email:</label>
                        <input type="email" name="customer_email" id="customer_email" class="form-control"
                            value="{{ $user->customer_email }}" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="customer_image">Avatar:</label>
                        <input type="file" name="customer_image" id="customer_image" class="form-control">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-password">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ URL::to('/u/save-change-password') }}" method="post" id="form-change-password">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Change Password') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{ __('Old password') }}</label>
                        <input type="password" class="form-control" name="old_password" id="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('New password') }}</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Repeat new password') }}</label>
                        <input type="password" class="form-control" name="repeat_new_password" id="repeat_new_password"
                            required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@push('add-script')
<script>
    $(function(){
            $('#form-update-info').validate({
                rules: {
                    customer_name: {
                        required: true,
                        minlength: 1,
                        maxlength: 50,
                    },
                    customer_address: {
                        required: true
                    },
                    customer_phone: {
                        required: true,
                    },
                    customer_image: {
                        extension: "jpg|jpeg|png|JPG|JPEG|PNG"
                    }
                },
                messages: {
                    category_name: {
                        required: "Please fill your name!",
                        minlength: "Please fill your name!",
                        maxlength: "Just less than 50 characters!"
                    },
                    customer_address: {
                        required: "Please fill address!"
                    },
                    customer_phone: {
                        required: "Please fill phone number!"
                    },
                    customer_image: {
                        extension: "This image not accept!"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#form-change-password').validate({
                rules: {
                    old_password: {
                        required: true,
                        minlength: 6,
                        maxlength: 50,
                    },
                    new_password: {
                        required: true,
                        minlength: 6,
                        maxlength: 50,
                    },
                    repeat_new_password: {
                        required: true,
                        minlength: 6,
                        maxlength: 50,
                        equalTo: "#new_password",
                    }
                },
                messages: {
                    old_password: {
                        required: "Please fill old password!",
                        minlength: "More than 6 characters!",
                        maxlength: "Just less than 50 characters!"
                    },
                    new_password: {
                        required: "Please fill new password!",
                        minlength: "More than 6 characters!",
                        maxlength: "Just less than 50 characters!"
                    },
                    repeat_new_password: {
                        required: "Please fill repeat password!",
                        minlength: "More than 6 characters!",
                        maxlength: "Just less than 50 characters!",
                        equalTo: "Repeat password not correct!"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
</script>
@endpush
@endsection