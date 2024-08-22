@extends('user.layout.app')

@section('content')
<div class="container mt-4">
    @foreach($postsByLevel as $level => $posts)
        <h2>Level {{ $level }}</h2>
        <div class="row">
            @foreach($posts->chunk(3) as $chunk)
                @foreach($chunk as $post)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body d-flex">
                                <div>
                                    @if($post->topic->category == 1)
                                        <i class="fas fa-book mr-2"></i> <!-- Icon cuốn sách -->
                                    @elseif($post->topic->category == 2)
                                        <i class="fas fa-search mr-2"></i> <!-- Icon kính lúp -->
                                    @endif
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">
                                        <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endforeach
</div>
@endsection
