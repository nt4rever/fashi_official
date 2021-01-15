@extends('layout')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ URL::to('/home') }}"><i class="fa fa-home"></i> {{ __('Home') }}</a>
                    <span>FAQs</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Faq Section Begin -->
<div class="faq-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="faq-accordin">
                    <div class="accordion" id="accordionExample">
                        @isset($faq)
                        @foreach ($faq as $item)
                        <div class="card">
                            <div class="card-heading active">
                                <a class="active" data-toggle="collapse" data-target="#collapse{{ $item->faq_id }}">
                                    {{ $item->faq_question }}
                                </a>
                            </div>
                            <div id="collapse{{ $item->faq_id }}" class="collapse" data-parent="#accordionExample">
                                <div class="card-body">
                                    <p>{!! $item->faq_answer !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endisset
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
</div>
<!-- Faq Section End -->
@push('add-script')
<script>
    $(function(){
            $('.nav-menu ul li').removeClass('active');
            $('.nav-menu ul li').eq(4).addClass('active');
        });
</script>
@endpush
@endsection