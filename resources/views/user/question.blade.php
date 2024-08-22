@extends('user.layout.app')

@section('content')
<div class="container mt-4">
    <div class="exam-header mb-4">
        <h3>{{ $exam->title }}</h3>
    </div>

    <form id="exam-form" class="mb-4">
        @csrf
        @foreach ($questions as $question)
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="question-title">
                    {!! __('messages.Question') !!}:
                    @foreach (preg_split('//u', $question->question_text, null, PREG_SPLIT_NO_EMPTY) as $char)
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
                        <div class="modal-dialog" role="document">
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
                @php
                $allAnswers = $question->answer->concat($question->wrong_answer)->shuffle();
                $alphabet = range('a', 'z');
                @endphp
                <div class="answers-container">
                    @foreach ($allAnswers as $index => $answer)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer-{{ $question->id }}-{{ $answer->id }}" value="{{ $answer->id }}">
                        <label class="form-check-label" for="answer-{{ $question->id }}-{{ $answer->id }}">
                            {{ $alphabet[$index] }}. {{ $answer->answer_text ?? $answer->wrong_answer_text }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <div class="text-center">
            <button type="button" class="btn btn-primary" id="submit-button">{{ __('messages.Submit') }}</button>
        </div>
    </form>

    <div class="text-center">
        <a href="{{ route('index') }}" class="btn btn-secondary">{{ __('messages.Back') }}</a>
    </div>
</div>

<script>
    function playAudio(url) {
        var audio = new Audio(url);
        audio.play();
    }
    document.getElementById('submit-button').addEventListener('click', function() {
        var formData = new FormData(document.getElementById('exam-form'));
        fetch("{{ route('question.submit', $exam->id) }}", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const results = data.results;
                for (const questionId in results) {
                    if (results.hasOwnProperty(questionId)) {
                        const resultText = results[questionId] === 'correct' ? 'Correct!' : 'Incorrect!';
                        const resultColor = results[questionId] === 'correct' ? 'green' : 'red';

                        // Find the selected answer and update its style
                        const selectedAnswerId = formData.get(`answers[${questionId}]`);
                        const answerElement = document.querySelector(`label[for="answer-${questionId}-${selectedAnswerId}"]`);
                        if (answerElement) {
                            answerElement.style.color = resultColor;
                            answerElement.style.fontWeight = 'bold';
                        }

                        // Disable all answers if the answer is correct
                        if (results[questionId] === 'correct') {
                            const answerInputs = document.querySelectorAll(`input[name="answers[${questionId}]"]`);
                            answerInputs.forEach(input => {
                                input.disabled = true;
                            });
                        }
                    }
                }
                // Update progress display
                const progress = data.progress;
                document.querySelector('.exam-header h4').innerText = `{{ __('messages.Your Progress') }}: ${progress}%`;
            })
            .catch(error => console.error('Error:', error));
    });

    // Initialize Bootstrap tooltips
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection

<style>
    .kanji-modal-trigger {
        position: relative;
        cursor: pointer;
        text-decoration: underline;
    }

    .modal-content {
        max-width: 500px;
        /* Adjust width as needed */
    }
</style>