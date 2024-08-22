@extends('admin.layout.app')

@section('content')
    <h1>Create New Post</h1>

    <form method="POST" action="{{ route('topic.update', $topics->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Common fields for both types -->
        <div class="form-group">
            <label for="title">tên bài viết</label>
            <input type="text" class="form-control" id="topics_name" name="topics_name" value="{{ $topics->name }}" required>
        </div>

        <!-- Additional fields for type 1 (content) -->

        @foreach($topics->post as $post)
                <div class="form-group">
                    <label for="post_title">Tiêu đề của bài</label>
                    <input type="text" class="form-control" id="post_title" name="post_title" value="{{ $post->title }}" required>
                </div>

                <div class="form-group">
                    <label for="post_description">Ý nhỏ 1</label>
                    <input type="text" class="form-control" id="post_description" name="post_description" value="{{ $post->description }}" required>
                </div>
            @if ($topics->category == 1)
                @foreach ($post->content as $content)
                    <div class="form-group">
                        <label for="content_description">Ý chính</label>
                        <textarea type="text" class="form-control" id="content_description" name="content_description" value="{{ $content->description }}" required>
                    </div>

                    <div class="form-group">
                        <label for="item_image">Image:</label>
                        <input type="file" name="item_image" id="item_image" class="form-control-file">
                    </div>
                    @if ($content->image)
                        <div class="form-group">
                            <label>Current Image:</label>
                            <img src="{{ asset('img/' . $content->image->url) }}" alt="" class="img-thumbnail" style="width: 50px; height: 50px;">
                        </div>
                    @endif
                @endforeach
            @endif
            <!-- Additional fields for type 2 (vocab) -->
            @if ($topics->category == 2)
                @foreach ($post->vocab as $vocab)
                    <div class="form-group">
                        <label for="word">Từ vựng</label>
                        <input type="text" class="form-control" id="word" name="word" value="{{ $vocab->word }}" required>
                    </div>

                    <div class="form-group">
                        <label for="meaning">Kanji</label>
                        <input type="text" class="form-control" id="word" name="kanji" value="{{ $vocab->kanji }}" required>
                    </div>

                    <div class="form-group">
                        <label for="example">Nghĩa</label>
                        <input type="text" class="form-control" id="word" name="meaning" value="{{ $vocab->meaning }}" required>  
                    </div>
                    <div class="form-group">
                        <label for="example">Ví dụ</label>
                        <input type="text" class="form-control" id="word" name="example" value="{{ $vocab->example }}" required>  
                    </div>
                    <div class="form-group">
                        <label for="item_image">Image:</label>
                        <input type="file" name="item_image" id="item_image" class="form-control-file">
                    </div>
                    @if ($vocab->image)
                        <div class="form-group">
                            <label>Current Image:</label>
                            <img src="{{ asset('img/' . $vocab->image->url) }}" alt="" class="img-thumbnail" style="width: 50px; height: 50px;">
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach

        <button type="submit" class="btn btn-primary">update</button>
    </form>
@endsection
