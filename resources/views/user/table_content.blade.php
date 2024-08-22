@extends('user.layout.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Left Column -->
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

        <!-- Main Content Column -->
        <div class="col-md-8">
            <div class="post-header">
                <h2 class="post-title">{{ $post->title }}</h2>
                <p class="post-description">{{ $post->description }}</p>
            </div>

            <div class="contents">
                @foreach($contents as $content)
                    <div class="content-item mb-4">
                        <p class="content-description">{!! nl2br(e($content->description)) !!}</p>
                        @if($content->image)
                            <img src="{{ asset('/img/' . $content->image->url) }}" alt="Content Image" class="img-fluid">
                        @endif
                    </div>
                @endforeach
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

        <!-- Right Column -->
        <div class="col-md-2">
            <h2 class="text-center">{{ __('messages.Related Posts') }}</h2>
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
    </div><!-- end row -->
</div><!-- end container -->
<script>
$(document).ready(function() {
    $('#comment-form').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('posts.saveComment', $post->id) }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#comments-list').append(`
                        <li class="comment-item mb-3">
                            <div class="comment-content p-3 border rounded">
                                <p>${response.comment.content}</p>
                                <small class="text-muted">Posted by: ${response.comment.user}</small>
                            </div>
                        </li>
                    `);

                    $('#comment-form')[0].reset();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(response) {
                alert('An error occurred while submitting your comment.');
            }
        });
    });
});
</script>
@endsection
