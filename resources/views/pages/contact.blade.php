@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <span>{{ __('Contact') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->
@if (!empty($contact))
<!-- Map Section Begin -->
<div class="map spad">
    <div class="container">
        <div class="map-inner">
            {!! $contact->map_frame !!}
            {{-- <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3835.7388104523366!2d108.2510487146829!3d15.97501058893922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3142108997dc971f%3A0x1295cb3d313469c9!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgVGjDtG5nIHRpbiB2w6AgVHJ1eeG7gW4gdGjDtG5nIFZp4buHdCAtIEjDoG4!5e0!3m2!1svi!2s!4v1605354365552!5m2!1svi!2s"
                height="610" style="border:0" allowfullscreen="">
            </iframe> --}}
        </div>
    </div>
</div>
<!-- Map Section Begin -->

<!-- Contact Section Begin -->
<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="contact-title">
                    <h4>{{ __('Contacts Us') }}</h4>
                    <p>{!! $contact->introduce !!}</p>
                </div>
                <div class="contact-widget">
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-location-pin"></i>
                        </div>
                        <div class="ci-text">
                            <span>{{ __('Address') }}:</span>
                            <p>{{ $contact->address }}</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="ci-text">
                            <span>{{ __('Phone') }}:</span>
                            <p>{{ $contact->phone }}</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-email"></i>
                        </div>
                        <div class="ci-text">
                            <span>Email:</span>
                            <p>{{ $contact->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-form">
                    <div class="leave-comment">
                        @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                        @endif
                        <h4>{{ __('Do you have any question') }}?</h4>
                        <p>{{ __('Our staff will call back later and answer your questions') }}.</p>
                        <form action="{{ URL::to('/ad/add-question-customer') }}" class="comment-form" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ __('Your name') }}" name="name" required autocomplete="off">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ __('Your email') }}" name="email" required
                                        autocomplete="off">
                                </div>
                                <div class="col-lg-12">
                                    <textarea placeholder="{{ __('Your question') }}" name="question" required></textarea>
                                    <button type="submit" class="site-btn">{{ __('Send') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->
@else
Chưa thêm thông tin trang liên hệ
@endif

@push('add-script')
<script>
    $(function(){
            $('.nav-menu ul li').removeClass('active');
            $('.nav-menu ul li').eq(3).addClass('active');
        });
</script>
@endpush
@endsection