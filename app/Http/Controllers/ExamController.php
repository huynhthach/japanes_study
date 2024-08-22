<?php

namespace App\Http\Controllers;

use App\Models\answers;
use Illuminate\Http\Request;
use App\Models\exams;
use App\Models\question;
use App\Models\wrong_answer;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        $itemsPerPage = 10;
        $currentPage = $request->query('page', 1); // Trang hiện tại, mặc định là trang 1
        $startDate = $request->input('year'); // Lấy năm từ input
        
        if ($startDate) {
            $query = exams::where('year', $startDate);
        } else {
            $query = exams::query()->orderBy('created_at', 'desc');
        }
    
        // Đếm tổng số dòng trong bảng dữ liệu
        $totalRows = $query->count();
    
        // Tính toán số lượng trang
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Tính toán vị trí bắt đầu của dữ liệu trong truy vấn
        $offset = ($currentPage - 1) * $itemsPerPage;
        $items = $query->offset($offset)->limit($itemsPerPage)->get();

    
        return view('admin.management.createExam', [
            'exams' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'startDate' => $startDate // Truyền giá trị loại thẻ đã lọc vào view
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function handleCreateForm(Request $request)
    {
        $numQuestions = $request->input('num_questions');
        $examTitle = $request->input('exam_title');
        $examLevel = $request->input('exam_level');
        return view('admin.function.exams.create_question', ['numQuestions' => $numQuestions, 'examTitle' => $examTitle, 'examLevel' => $examLevel]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exam = exams::create([
            'title' => $request->input('exam_title'),
            'level' => $request->input('exam_level'),
            'year' => $request->input('year'),
            'created_at' => now(),
        ]);

        $questions = $request->input('questions');

        foreach ($questions as $questionData) {
            $question = $exam->question()->create([
                'exam_id' => $exam->id,
                'question_text' => $questionData['text'],
                'created_at' => now(),
            ]);

            $question->answer()->create([
                'question_id' => $question->id,
                'answer_text' => $questionData['correct_answer'],
                'is_correct' => true,
                'created_at' => now(),
            ]);

            foreach ($questionData['wrong_answers'] as $wrongAnswerText) {
                $question->wrong_answer()->create([
                    'question_id' => $question->id,
                    'wrong_answer_text' => $wrongAnswerText,
                    'is_correct' => false,
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = exams::findOrFail($id);
        $question = $exam->question()->get();
        return view('admin.function.exams.showExam', compact('exam', 'question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = exams::findOrFail($id);
        $questions = $exam->question()->get();
        return view('admin.function.exams.editExam', compact('exam', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $exam = exams::findOrFail($id);
        $exam->update([
            'title' => $request->input('exam_title'),
            'level' => $request->input('exam_level'),
            'year' => $request->input('year'),
        ]);

        // Fetch existing questions and their ids
        $existingQuestions = $exam->question->pluck('id')->toArray();
        $submittedQuestions = array_keys($request->input('questions'));

        // Determine questions to delete
        $questionsToDelete = array_diff($existingQuestions, $submittedQuestions);

        // Delete questions that are no longer present
        foreach ($questionsToDelete as $questionId) {
            $question = Question::findOrFail($questionId);
            $question->delete();
        }

        foreach ($request->input('questions') as $questionId => $questionData) {
            $question = Question::findOrFail($questionId);
            $question->update([
                'question_text' => $questionData['question_text'],
            ]);

            if (isset($questionData['answers'])) {
                foreach ($questionData['answers'] as $answerId => $answerText) {
                    $answer = answers::findOrFail($answerId);
                    $answer->update([
                        'answer_text' => $answerText,
                    ]);
                }
            }

            if (isset($questionData['wrong_answers'])) {
                foreach ($questionData['wrong_answers'] as $wrongAnswerId => $wrongAnswerText) {
                    $wrongAnswer = wrong_answer::findOrFail($wrongAnswerId);
                    $wrongAnswer->update([
                        'wrong_answer_text' => $wrongAnswerText,
                    ]);
                }
            }
        }

        // Handle new questions
        if ($request->has('new_questions')) {
            foreach ($request->input('new_questions') as $newQuestionData) {
                $newQuestion = Question::create([
                    'exam_id' => $exam->id,
                    'question_text' => $newQuestionData['question_text'],
                ]);

                foreach ($newQuestionData['answers'] as $newAnswerText) {
                    answers::create([
                        'question_id' => $newQuestion->id,
                        'answer_text' => $newAnswerText,
                        'is_correct' => 1,
                    ]);
                }

                foreach ($newQuestionData['wrong_answers'] as $newWrongAnswerText) {
                    wrong_answer::create([
                        'question_id' => $newQuestion->id,
                        'wrong_answer_text' => $newWrongAnswerText,
                        'is_correct' => 0,
                    ]);
                }
            }
        }

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = exams::findOrFail($id);
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }
}
