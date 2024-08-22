@extends('user.layout.app')

@section('content')
    <div class="container mt-5">
        <h1>{{ __('messages.Contact Us') }}</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('translate') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="text">{{ __('messages.Text') }}</label>
                <textarea id="text" name="text" class="form-control" rows="5" required></textarea>
            </div>
            @guest
            <div class="form-group" style="width: 300px;">
                <label for="email">{{ __('messages.Email') }}</label>
                <input type="email" id="email" name="emails" class="form-control" required>
            </div>
            @endguest
            <button type="submit" class="btn btn-primary">{{ __('messages.Send') }}</button>

        </form>
    </div>

@endsection
