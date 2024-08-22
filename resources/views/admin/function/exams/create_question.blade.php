@extends('admin.layout.app')
    <title>Create Questions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@section('content')
<div class="container">
        <h1>Tạo câu hỏi cho bộ đề: {{ $examTitle }}</h1>
        <form action="{{ route('exams.store') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_title" value="{{ $examTitle }}">
            <input type="hidden" name="exam_level" value="{{ $examLevel }}">
            <input type="hidden" name="num_questions" value="{{ $numQuestions }}">
            <div class="form-group">
                <label for="year">Năm của bộ đề:</label>
                <input type="text" class="form-control" name="year" placeholder="Năm của bộ đề" required style="width: 200px;">
            </div>
            <div class="row">
                @for ($i = 0; $i < $numQuestions; $i++)
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3>Question {{ $i + 1 }}</h3>
                                <div class="form-group">
                                    <label for="questions[{{ $i }}][text]">Question:</label>
                                    <input type="text" class="form-control" name="questions[{{ $i }}][text]" required>
                                </div>
                                <h4 style="color: green;">Correct Answer</h4>
                                <div class="form-group">
                                    <label for="questions[{{ $i }}][correct_answer]">Correct Answer:</label>
                                    <input type="text" class="form-control" name="questions[{{ $i }}][correct_answer]" required>
                                </div>
                                <h4 style="color: red;">Wrong Answers</h4>
                                @for ($j = 0; $j < 3; $j++)
                                    <div class="form-group">
                                        <label for="questions[{{ $i }}][wrong_answers][{{ $j }}]">Wrong Answer {{ $j + 1 }}:</label>
                                        <input type="text" class="form-control" name="questions[{{ $i }}][wrong_answers][{{ $j }}]" required>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    @endsection