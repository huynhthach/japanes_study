<?php

namespace App\Http\Controllers;

use App\Helpers\TranslationHelper;
use App\Models\exams;
use App\Models\image;
use App\Models\post;
use App\Models\question;
use App\Models\mycourse;
use App\Models\vocab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MainPageController extends Controller
{
    public function index(){
        $images = image::orderBy('created_at', 'asc')->take(2)->get();
        $user = Auth::user();
        $targetLang = Session::get('locale', 'en'); // Ví dụ: mặc định là 'en'
    
        $userProgress = null;
        $examTitle = null;
    
        $content = vocab::orderBy('created_at', 'desc')->take(1)->get();
    
        // Lặp qua các câu hỏi và lấy các từ kanji
        $kanjiList = [];
        foreach ($content as $contents) {
            preg_match_all('/[\x{4E00}-\x{9FBF}]/u', $contents->kanji, $matches);
            $kanjiList = array_merge($kanjiList, $matches[0]);
        }
        $kanjiList = array_unique($kanjiList);
    
        // Kiểm tra và lấy dữ liệu Kanji từ session
        $kanjiMeanings = Session::get('kanji_meanings', []);
    
        // Nếu dữ liệu không tồn tại trong session, gọi API và lưu vào session
        if (empty($kanjiMeanings)) {
            foreach ($kanjiList as $kanji) {
                $response = Http::withHeaders([
                    'X-RapidAPI-Host' => 'kanjialive-api.p.rapidapi.com',
                    'X-RapidAPI-Key' => '7cb70fc23dmshe5efc600176b78cp1ee3dfjsn225db6e1d191'
                ])->get("https://kanjialive-api.p.rapidapi.com/api/public/kanji/{$kanji}");
    
                if ($response->successful()) {
                    $kanjiMeanings[$kanji] = $response->json();
                }
            }
            // Lưu dữ liệu vào session
            Session::put('kanji_meanings', $kanjiMeanings);
        } else {
            // Lọc lại danh sách kanji để chỉ giữ lại các kanji không có trong session
            $kanjiList = array_filter($kanjiList, function($kanji) use ($kanjiMeanings) {
                return !isset($kanjiMeanings[$kanji]);
            });
    
            // Gọi API cho các kanji còn thiếu và lưu lại vào session
            foreach ($kanjiList as $kanji) {
                $response = Http::withHeaders([
                    'X-RapidAPI-Host' => 'kanjialive-api.p.rapidapi.com',
                    'X-RapidAPI-Key' => '7cb70fc23dmshe5efc600176b78cp1ee3dfjsn225db6e1d191'
                ])->get("https://kanjialive-api.p.rapidapi.com/api/public/kanji/{$kanji}");
    
                if ($response->successful()) {
                    $kanjiMeanings[$kanji] = $response->json();
                }
            }
    
            // Cập nhật lại session với các kanji mới
            Session::put('kanji_meanings', $kanjiMeanings);
        }
    
        $formattedVocabs = $content->map(function($vocab) use ($targetLang, &$kanjiMeanings) {
            $words = explode(' ', $vocab->word);
            $kanjis = explode(' ', $vocab->kanji);
            $meanings = explode(',', $vocab->meaning);
    
            // Dịch nghĩa và ví dụ
            $meaningTranslations = array_map(function($meaning) use ($targetLang) {
                return TranslationHelper::translate($meaning, $targetLang);
            }, $meanings);
    
            $exampleTranslation = TranslationHelper::translate($vocab->example, $targetLang);
    
            return collect($words)->map(function($word, $index) use ($kanjis, $meaningTranslations) {
                return [
                    'word' => $word,
                    'kanji' => $kanjis[$index] ?? '',
                    'meaning' => $meaningTranslations[$index] ?? '',
                ];
            })->all();
        });

    
        if (Auth::check()) {
            $course = mycourse::where('user_id', $user->id)->first();
            if ($course) {
                $userProgress = $course->progress;
                $examTitle = $course->exam->title;
            }
        }    
        return view('user.index', compact('images', 'userProgress', 'examTitle', 'formattedVocabs', 'kanjiMeanings'));
    }
    

    public function Exam($id){
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để làm bài tập.');
        }

        $exam = exams::where('level',$id)->orderBy('year', 'desc')->get();
        return view('user.exams',compact('exam'));
    }

    public function Question($id){
        $exam = exams::findOrFail($id);
        $questions = $exam->question;
        $user = Auth::user();
    
        // Get user's progress
        $course = mycourse::where('user_id', $user->id)->where('exam_id', $exam->id)->first();
        $progress = $course ? $course->progress : 0;
    
        // Lấy ngôn ngữ mục tiêu từ session
        $targetLang = Session::get('locale', 'en');
    
        // Lặp qua các câu hỏi và lấy các từ kanji
        $kanjiList = [];
        foreach ($questions as $question) {
            preg_match_all('/[\x{4E00}-\x{9FBF}]/u', $question->question_text, $matches);
            $kanjiList = array_merge($kanjiList, $matches[0]);
        }
        $kanjiList = array_unique($kanjiList);
    
        // Kiểm tra dữ liệu kanji trong session
        $kanjiMeanings = Session::get('kanji_meanings', []);
    
        // Lọc ra các kanji chưa có trong session
        $kanjiToFetch = array_diff($kanjiList, array_keys($kanjiMeanings));
    
        // Gọi API để lấy nghĩa của các từ kanji chưa có trong session
        foreach ($kanjiToFetch as $kanji) {
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'kanjialive-api.p.rapidapi.com',
                'X-RapidAPI-Key' => '7cb70fc23dmshe5efc600176b78cp1ee3dfjsn225db6e1d191'
            ])->get("https://kanjialive-api.p.rapidapi.com/api/public/kanji/{$kanji}");
    
            if ($response->successful()) {
                $kanjiMeanings[$kanji] = $response->json();
            }
        }
    
        // Lưu dữ liệu kanji vào session
        Session::put('kanji_meanings', $kanjiMeanings);
    
        return view('user.question', compact('questions', 'exam', 'progress', 'kanjiMeanings', 'targetLang'));
    }

    public function submitAnswers(Request $request, $id) {
        $exam = exams::findOrFail($id);
        $questions = $exam->question;
        $userAnswers = $request->input('answers');
        $user = Auth::user();

        $results = [];
        $correctCount = 0;
        foreach ($questions as $question) {
            $correctAnswer = $question->answer()->where('is_correct', true)->first();
            if (isset($userAnswers[$question->id]) && $userAnswers[$question->id] == $correctAnswer->id) {
                $results[$question->id] = 'correct';
                $correctCount++;
            } else {
                $results[$question->id] = 'incorrect';
            }
        }

        // Calculate progress
        $progress = ($correctCount / $questions->count()) * 100;
        mycourse::updateProgress($user->id, $exam->id, $progress);

        return response()->json(['results' => $results, 'progress' => $progress]);
    }

    public function getUserProgress()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $course = mycourse::where('user_id', $user->id)->first();
            $exam = $course ? exams::find($course->exam_id) : null;
            $progress = $course ? $course->progress : 0;
    
            return response()->json([
                'exam' => $exam ? $exam->title : 'No exam',
                'progress' => $progress,
            ]);
        }
    
        return response()->json([
            'message' => 'Not logged in'
        ], 401);
    }
    
    public function about_us(){
        
        return view('user.about_us');
    }
}
