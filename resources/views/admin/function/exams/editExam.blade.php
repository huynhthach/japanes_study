@extends('admin.layout.app')

@section('content')
<h1>Chỉnh sửa bộ đề</h1>

<form action="{{ route('exams.update', $exam->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="exam_title">Tên bộ đề</label>
        <input type="text" class="form-control" id="exam_title" name="exam_title" value="{{ $exam->title }}" required>
    </div>

    <div class="form-group">
        <label for="exam_level">Level</label>
        <select name="exam_level" id="exam_level" class="form-control" required>
            <option value="N5" {{ $exam->level == 'N5' ? 'selected' : '' }}>N5</option>
            <option value="N4" {{ $exam->level == 'N4' ? 'selected' : '' }}>N4</option>
            <option value="N3" {{ $exam->level == 'N3' ? 'selected' : '' }}>N3</option>
            <option value="N2" {{ $exam->level == 'N2' ? 'selected' : '' }}>N2</option>
            <option value="N1" {{ $exam->level == 'N1' ? 'selected' : '' }}>N1</option>
        </select>
    </div>

    <div class="form-group">
        <label for="year">Year</label>
        <input type="number" class="form-control" id="year" name="year" value="{{ $exam->year }}" required>
    </div>

    <h2>Các câu hỏi</h2>
    <div id="questions-container">
        @foreach ($questions as $question)
        <div class="form-group question-group" id="question-group-{{ $question->id }}">
            <label for="question_text_{{ $question->id }}" style="font-size: larger;">Câu hỏi</label>
            <input type="text" class="form-control" id="question_text_{{ $question->id }}" name="questions[{{ $question->id }}][question_text]" value="{{ $question->question_text }}" required>

            <h3>Các câu trả lời đúng</h3>
            @foreach ($question->answer as $answer)
            <div class="form-group">
                <label for="answer_text_{{ $answer->id }}">Câu trả lời</label>
                <input type="text" class="form-control" id="answer_text_{{ $answer->id }}" name="questions[{{ $question->id }}][answers][{{ $answer->id }}]" value="{{ $answer->answer_text }}" required>
            </div>
            @endforeach

            <h3>Các câu trả lời sai</h3>
            @foreach ($question->wrong_answer as $wrong_answer)
            <div class="form-group">
                <label for="wrong_answer_text_{{ $wrong_answer->id }}">Câu trả lời sai</label>
                <input type="text" class="form-control" id="wrong_answer_text_{{ $wrong_answer->id }}" name="questions[{{ $question->id }}][wrong_answers][{{ $wrong_answer->id }}]" value="{{ $wrong_answer->wrong_answer_text }}" required>
            </div>
            @endforeach
            <button type="button" class="btn btn-danger btn-remove-question" data-question-id="{{ $question->id }}">Xóa câu hỏi</button>
            <hr>
        </div>
        @endforeach

    </div>

    <div>
        <button type="button" class="btn btn-secondary btn-custom" id="add-question">Thêm câu hỏi mới</button>
        <button type="submit" class="btn btn-primary btn-custom">Cập nhật</button>
    </div>
</form>

<script>
    document.getElementById('add-question').addEventListener('click', function() {
        const questionIndex = document.querySelectorAll('.question-group').length + 1;

        const newQuestion = `
            <div class="form-group question-group" id="new-question-group-${questionIndex}">
                <label for="new_question_text_${questionIndex}" style="font-size: larger;">Câu hỏi</label>
                <input type="text" class="form-control" id="new_question_text_${questionIndex}" name="new_questions[${questionIndex}][question_text]" required>

                <h3>Các câu trả lời đúng</h3>
                <div class="form-group">
                    <label for="new_answer_text_${questionIndex}">Câu trả lời</label>
                    <input type="text" class="form-control" id="new_answer_text_${questionIndex}" name="new_questions[${questionIndex}][answers][]" required>
                </div>

                <h3>Các câu trả lời sai</h3>
                ${[...Array(3)].map((_, i) => `
                <div class="form-group">
                    <label for="new_wrong_answer_text_${questionIndex}_${i}">Câu trả lời sai ${i + 1}</label>
                    <input type="text" class="form-control" id="new_wrong_answer_text_${questionIndex}_${i}" name="new_questions[${questionIndex}][wrong_answers][]" required>
                </div>`).join('')}
                <button type="button" class="btn btn-danger btn-remove-new-question" data-question-index="${questionIndex}">Xóa câu hỏi</button>
                <hr>
            </div>
        `;

        document.getElementById('questions-container').insertAdjacentHTML('beforeend', newQuestion);
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-remove-question')) {
            const questionId = event.target.getAttribute('data-question-id');
            document.getElementById(`question-group-${questionId}`).remove();
        } else if (event.target.classList.contains('btn-remove-new-question')) {
            const questionIndex = event.target.getAttribute('data-question-index');
            document.getElementById(`new-question-group-${questionIndex}`).remove();
        }
    });
</script>
@endsection
