@extends('user.layout.app')

@section('content')
<div class="about-us-container">
    <div class="content-section">
        <div class="container">
        @if(session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
        @endif
            <div class="row mb-5">
                <div class="col-md-6">
                    <h2>{{ __('messages.Our Story') }}</h2>
                    <p>{{ __('messages.Our Story Description') }}</p>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('img/about/1.png') }}" alt="Our Story" class="img-fluid">
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <img src="{{ asset('img/about/2.png') }}" alt="Our Mission" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <h2>{{ __('messages.Our Mission') }}</h2>
                    <p>{{ __('messages.Our Mission Description') }}</p>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <h2>{{ __('messages.Our Team') }}</h2>
                    <p>{{ __('messages.Our Team Description') }}</p>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('img/about/3.png') }}" alt="Our Team" class="img-fluid">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>{{ __('messages.Contact Us') }}</h2>
                    <p>{{ __('messages.Contact Us Description') }}</p>
                    <a href="{{ route('index_translate') }}" class="btn btn-primary">{{ __('messages.Contact Us') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
