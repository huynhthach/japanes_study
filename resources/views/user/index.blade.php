@extends('user.layout.app')

@section('title', 'Trang chủ')

@section('content')
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @foreach($images as $index => $image)
        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach($images as $index => $image)
        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
            <img class="d-block w-100" src="{{ asset('/img/' . $image->url) }}" alt="Slide {{ $index + 1 }}">
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="row mt-4">
    <div class="col-md-12 d-flex justify-content-center">
        <div class="jumbotron text-center">
            <h1 class="display-4">{{ __('messages.learn_japanese') }}</h1>
            <p class="lead">{{ __('messages.welcome') }}</p>
            <hr class="my-4" style="border-top: 5px dotted red;">
            <p>{{ __('messages.start_learning') }}</p>
            @foreach(__('messages.course_levels') as $level => $name)
            <a href="{{ route('exam', $level) }}" class="btn rounded-circle m-2 level-button" data-level="{{ $level }}">{{ $name }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-5 d-flex justify-content-center">
        <div class="jumbotron text-center" id="progress-chart-container">
            @if(isset($userProgress))
            <h1 id="progress-message">{{ $examTitle }}</h1>
            <canvas id="progress-chart"></canvas>
            @else
            <h1 id="progress-message">{{ __('messages.login_to_see') }}</h1>
            @endif
        </div>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function() {
                // Fetch user progress data via AJAX
                $.ajax({
                    url: "{{ route('user.progress') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.message) {
                            $('#progress-message').text(response.message);
                        } else {
                            $('#progress-message').text(response.exam);

                            // Create a pie chart using Chart.js
                            var ctx = document.getElementById('progress-chart').getContext('2d');
                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Progress', 'Remaining'],
                                    datasets: [{
                                        data: [response.progress, 100 - response.progress],
                                        backgroundColor: ['#36a2eb', '#ff6384'],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(tooltipItem) {
                                                    return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', error);
                    }
                });
            });
        </script>
    </div>

    <div class="col-md-3 d-flex justify-content-center">
        <!-- Add your content here if needed -->
    </div>

    <div class="col-md-4 d-flex justify-content-center">
        <div class="jumbotron text-center">
            <h3>{!! __('messages.Vocab_today') !!}:</h3>
            @foreach($formattedVocabs as $vocabGroup)
            @foreach($vocabGroup as $vocab)
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="question-title">
                        @foreach (preg_split('//u', $vocab['kanji'], null, PREG_SPLIT_NO_EMPTY) as $char)
                        @if (isset($kanjiMeanings[$char]))
                        @php
                        $meaning = $kanjiMeanings[$char]['kanji']['meaning']['english'] ?? '';
                        $kunyomi = $kanjiMeanings[$char]['kanji']['kunyomi']['hiragana'] ?? [];
                        $example = $kanjiMeanings[$char]['examples'][0]['japanese'] ?? [];
                        $meaningExam = $kanjiMeanings[$char]['examples'][0]['meaning']['english'] ?? [];
                        $audio = $kanjiMeanings[$char]['examples'][0]['audio']['mp3'] ?? '';
                        $onyomi = $kanjiMeanings[$char]['kanji']['onyomi']['katakana'] ?? [];
                        $video = $kanjiMeanings[$char]['kanji']['video']['mp4'] ?? '';
                        $modalTriggerId = 'kanji-modal-' . $char;
                        @endphp
                        <span class="kanji-modal-trigger" data-toggle="modal" data-target="#{{ $modalTriggerId }}">
                            {{ $char }}
                        </span>

                        <!-- Modal -->
                        <div class="modal fade" id="{{ $modalTriggerId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalTriggerId }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="{{ $modalTriggerId }}Label">{{ $char }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>{{ __('messages.Meaning') }}:</strong> {{ $meaning }}</p>
                                                @if (!empty($kunyomi))
                                                <p><strong>Kunyomi (Hiragana):</strong> {{ $kunyomi }}</p>
                                                @endif
                                                @if (!empty($onyomi))
                                                <p><strong>Onyomi (Katakana):</strong> {{ $onyomi }}</p>
                                                @endif
                                                @if (!empty($example))
                                                <p><strong>Example:</strong> {{ $example }} ({{ $meaningExam }})
                                                    @if (!empty($audio))
                                                    <button type="button" class="btn btn-link p-0" onclick="playAudio('{{ $audio }}')">
                                                        <i class="fas fa-volume-up"></i>
                                                    </button>
                                                    @endif
                                                </p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if (!empty($video))
                                                <p><strong>Video:</strong></p>
                                                <video class="w-100" controls>
                                                    <source src="{{ $video }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.Close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @else
                        {{ $char }}
                        @endif
                        @endforeach
                    </h4>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

<script>
    function playAudio(url) {
        var audio = new Audio(url);
        audio.play();
    }
</script>

</div>

@endsection