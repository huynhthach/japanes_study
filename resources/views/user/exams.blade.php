@extends('user.layout.app')

@section('content')
<!-- Container for exams -->
<div class="container mt-4">
    <div class="row">
        <!-- Jumbotron -->
        <div class="col-md-12 d-flex justify-content-center">
            <div class="jumbotron text-center jumbotron-custom">
                <h1 class="display-4">{{ __('messages.learn_japanese') }}</h1>
                <p class="lead">{{ __('messages.welcome') }}</p>
                <hr class="my-4">
                <p>{{ __('messages.start_learning') }}</p>
                <div class="button-container">
                    @foreach(__('messages.course_levels') as $level => $name)
                    <a href="{{ route('exam', $level) }}" class="btn level-button" data-level="{{ $level }}">{{ $name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Exam List -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="exam-list">
                @foreach ($exam as $index => $exams)
                <div class="exam-item">
                    <span class="exam-number">{{ $index + 1 }}. </span>
                    <a href="{{ route('question', $exams->id) }}" class="exam-link">{{ $exams->title }}</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <a href="{{ route('index') }}" class="btn btn-primary">{{ __('messages.Back') }}</a>
        </div>
    </div>
</div>

@endsection

