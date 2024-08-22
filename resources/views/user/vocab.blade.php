@extends('user.layout.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-2">
        <div class="list-group">
                @foreach($postsByLevel as $level => $posts)
                    <?php $postCount = $posts->count(); // Tính số lượng bài viết trong cấp độ ?>
                    <a href="#level{{ $level }}" class="list-group-item list-group-item-action" data-toggle="collapse">
                        Level {{ $level }} ({{ $postCount }})
                    </a>
                    <div id="level{{ $level }}" class="collapse">
                        @foreach($posts->chunk(3) as $chunk)
                            @foreach($chunk as $post1)
                                <div class="list-group-item">
                                    <a href="{{ route('posts.show', $post1->id) }}">
                                        @if($post1->topic->category == 1)
                                            <i class="fas fa-book mr-2"></i> <!-- Icon cuốn sách -->
                                        @elseif($post1->topic->category == 2)
                                            <i class="fas fa-search mr-2"></i> <!-- Icon kính lúp -->
                                        @endif
                                        {{ $post1->title }}
                                    </a>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-8">
            <!-- Main Content -->
            <div class="content">
                <h2 class="content-title">{{ $post->title }}</h2>
                <p class="content-description">{{ $post->description }}</p>

                @foreach($vocabs as $vocab)
                    @if($vocab->image)
                        <div class="text-center mb-4">
                            <img src="{{ asset('/img/' . $vocab->image->url) }}" alt="Post Image" class="img-fluid" style="max-width: 80%;">
                        </div>
                    @endif
                @endforeach

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Hiragana') }}</th>
                            <th>{{ __('Kanji') }}</th>
                            <th>{{ __('messages.Meaning') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formattedVocabs as $vocabGroup)
                            @foreach($vocabGroup as $vocab)
                                <tr>
                                    <td>{{ $vocab['word'] }}</td>
                                    <td>{{ $vocab['kanji'] }}</td>
                                    <td>{{ $vocab['meaning'] }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="comments mt-4">
                <h3 class="comments-title">{{ __('messages.Comment') }}</h3>
                <ul class="list-unstyled comments-list" id="comments-list">
                    @foreach($post->comment as $comment)
                    <li class="comment-item mb-3">
                        <div class="comment-content p-3 border rounded">
                            <p>{{ $comment->Content }}</p>
                            <small class="text-muted">{{ __('messages.Posted by') }}: {{ $comment->users->name }}</small>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div><!-- end comments -->

            <div class="comment-form mt-4">
                @auth <!-- Kiểm tra xem người dùng đã đăng nhập hay chưa -->
                <form id="comment-form">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" name="content" rows="3" placeholder="Your comment" style="color: black;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('messages.Submit_comment') }}</button>
                </form>
                @else
                <p>{{ __('messages.Please') }} <a href="{{ route('login') }}">{{ __('messages.Login') }}</a> {{ __('messages.to leave a comment.') }}</p>
                @endauth
            </div><!-- end comment-form -->
        </div>

        <div class="col-md-2">
        <h2 class="text-center">Related Posts</h2>
            <div class="list-group">
                @foreach($postsByLevel2 as $level => $posts)
                    @if($level == $category) <!-- Hiển thị chỉ các bài viết thuộc cấp độ hiện tại -->
                        @foreach($posts->chunk(3) as $chunk)
                            @foreach($chunk as $post)
                                <div class="list-group-item">
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        @if($post->topic->category == 1)
                                            <i class="fas fa-book mr-2"></i> <!-- Icon cuốn sách -->
                                        @elseif($post->topic->category == 2)
                                            <i class="fas fa-search mr-2"></i> <!-- Icon kính lúp -->
                                        @endif
                                        {{ $post->title }}
                                    </a>
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .container {
        margin-top: 20px;
    }
    .content {
        margin-bottom: 20px;
    }
    .content-title {
        font-size: 2rem; /* Increase font size for title */
        font-weight: bold;
        margin-bottom: 15px;
    }
    .content-description {
        font-size: 1.25rem; /* Increase font size for description */
        margin-bottom: 20px;
    }
    .table {
        width: 100%;
        margin-top: 20px;
    }
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        font-size: 1.1rem; /* Increase font size for table */
    }
    .img-fluid {
        max-width: 80%; /* Reduce the maximum width of images */
        height: auto;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
    }
    .col-md-3 {
        padding: 15px;
    }
    .col-md-6 {
        padding: 15px;
    }
</style>
