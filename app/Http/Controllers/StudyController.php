<?php

namespace App\Http\Controllers;

use App\Helpers\TranslationHelper;
use App\Models\commemt;
use App\Models\post;
use App\Models\table_content;
use App\Models\topics;
use App\Models\vocab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudyController extends Controller
{
    public function index()
    {
        $levels = ['N5', 'N4', 'N3', 'N2', 'N1'];
        $postsByLevel = [];

        // Đọc ngôn ngữ từ session
        $targetLang = Session::get('locale', 'en'); // Mặc định là 'en'

        foreach ($levels as $level) {
            $postsByLevel[$level] = topics::where('level', $level)
                ->with('post')
                ->get()
                ->flatMap(function ($topic) use ($targetLang) {
                    return $topic->post->map(function ($post) use ($targetLang) {
                        $post->title = TranslationHelper::translate($post->title, $targetLang);
                        $post->description = TranslationHelper::translate($post->description, $targetLang);
                        return $post;
                    });
                });
        }

        return view('user.posts', compact('postsByLevel'));
    }


    public function show($id)
    {
        $post = Post::findOrFail($id);
        $category = $post->topic->category;

        $levels = ['N5', 'N4', 'N3', 'N2', 'N1'];
        $postsByLevel = [];

        foreach ($levels as $level) {
            $postsByLevel[$level] = topics::where('level', $level)
                ->with('post')
                ->get()
                ->flatMap(function ($topic) {
                    return $topic->post;
                });
        }

        // Lấy các bài viết có cùng cấp độ
        $postsByLevel2 = Post::whereHas('topic', function ($query) use ($category) {
            $query->where('category', $category);
        })->get()->groupBy('topic.category');

        // Đọc ngôn ngữ từ session
        $targetLang = Session::get('locale', 'en'); // Ví dụ: mặc định là 'en'

        // Dịch tiêu đề và mô tả của bài viết
        $post->title = TranslationHelper::translate($post->title, $targetLang);
        $post->description = TranslationHelper::translate($post->description, $targetLang);

        // Dịch tiêu đề của các bài viết trong $postsByLevel2
        foreach ($postsByLevel2 as $category => $posts) {
            foreach ($posts as $postItem) {
                $postItem->title = TranslationHelper::translate($postItem->title, $targetLang);
            }
        }

        if ($category == 1) {
            $contents = $post->content()->get();

            // Dịch nội dung
            foreach ($contents as $content) {
                $content->description = TranslationHelper::translate($content->description, $targetLang);
            }

            return view('user.table_content', compact('post', 'contents', 'postsByLevel', 'postsByLevel2', 'category'));
        } elseif ($category == 2) {
            $vocabs = $post->vocab()->get();

            // Dịch từ vựng và ví dụ
            $formattedVocabs = $vocabs->map(function ($vocab) use ($targetLang) {
                $words = explode(' ', $vocab->word);
                $kanjis = explode(' ', $vocab->kanji);
                $meanings = explode(',', $vocab->meaning);

                // Dịch nghĩa và ví dụ
                $meaningTranslations = array_map(function ($meaning) use ($targetLang) {
                    return TranslationHelper::translate($meaning, $targetLang);
                }, $meanings);

                $exampleTranslation = TranslationHelper::translate($vocab->example, $targetLang);

                return collect($words)->map(function ($word, $index) use ($kanjis, $meaningTranslations) {
                    return [
                        'word' => $word,
                        'kanji' => $kanjis[$index] ?? '',
                        'meaning' => $meaningTranslations[$index] ?? '',
                    ];
                })->all();
            });

            return view('user.vocab', compact('post', 'vocabs', 'postsByLevel', 'postsByLevel2', 'category', 'formattedVocabs'));
        } else {
            return view('user.posts')->with('message', 'truy cập thất bại');
        }
    }



    public function saveComment(Request $request, $newsId)
    {
        // Validate dữ liệu nhập vào từ form comment
        $request->validate([
            'content' => 'required|max:255|min:2', // Đặt điều kiện phù hợp với yêu cầu của bạn
        ]);

        // Tạo một bản ghi comment mới
        $comment = new commemt();
        $comment->UserID = Auth::id(); // Lấy ID của người dùng hiện tại đăng nhập
        $comment->PostID = $newsId; // Lấy ID của tin tức mà comment được đăng trong đó
        $comment->Content = $request->input('content');
        $comment->Created_at = now(); // Sử dụng ngày giờ hiện tại

        // Lưu bản ghi comment vào cơ sở dữ liệu
        $comment->save();

        // Trả về JSON response
        return response()->json([
            'success' => true,
            'message' => 'Comment đã được đăng thành công',
            'comment' => [
                'content' => $comment->Content,
                'user' => Auth::user()->name,
                'created_at' => $comment->Created_at->toDateTimeString(),
            ]
        ]);
    }
}
