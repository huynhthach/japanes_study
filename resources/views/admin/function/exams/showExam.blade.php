@extends('admin.layout.app')

@section('content')
    <h1>Chi tiết bộ đề</h1>

    <div>
        <h3>Title: {{ $exam->title }}</h3>
        <h4>Level: {{ $exam->level }}</h4>
        <h4>Year: {{ $exam->year }}</h4>
    </div>

    <h2>Các câu hỏi</h2>
    @foreach ($question as $questions)
        <div>
            <h4>Câu hỏi: {{ $questions->question_text }}</h4>
            <ul>
                @foreach ($questions->answer as $answer)
                    <li style="color: green;">
                        {{ $answer->answer_text }}
                    </li>
                @endforeach

                @foreach ($questions->wrong_answer as $wrong_answer)
                    <li style="color: red;">
                        {{ $wrong_answer->wrong_answer_text }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <a href="{{ route('exams.index') }}" class="btn btn-primary">Quay lại</a>
@endsection
