@extends('admin.layout.app')

@section('content')
    <h1>Create New Post</h1>

    <form id="post-form" method="POST" action="{{ route('topic.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="post_title" value="{{ $postTitle }}">
        <input type="hidden" name="post_level" value="{{ $postLevel }}">
        <input type="hidden" name="level" value="{{ $level }}">

        <!-- Common fields for both types -->
        <div class="form-group">
            <label for="title">Title</label>
            <textarea class="form-control" id="title" name="title" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        <!-- Additional fields for type 1 (content) -->
        @if ($postLevel == 1)
            <div class="form-group">
                <label for="post_description">Post Description</label>
                <textarea class="form-control" id="post_description" name="post_description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            </div>
        @endif

        <!-- Additional fields for type 2 (vocab) -->
        @if ($postLevel == 2)
            <div class="form-group">
                <label for="word">Word</label>
                <input type="text" class="form-control" id="word" name="word" required>
            </div>

            <div class="form-group">
                <label for="kanji">Kanji</label>
                <input type="text" class="form-control" id="kanji" name="kanji" required>
            </div>

            <div class="form-group">
                <label for="meaning">Meaning</label>
                <textarea class="form-control" id="meaning" name="meaning" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="example">Example</label>
                <textarea class="form-control" id="example" name="example" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
