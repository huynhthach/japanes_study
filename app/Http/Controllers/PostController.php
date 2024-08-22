<?php

namespace App\Http\Controllers;

use App\Models\topics;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest; // Import Form Request
use App\Jobs\TranslateTextMK2;
use App\Models\image;
use App\Models\post;
use App\Models\table_content;
use App\Models\translate;
use App\Models\vocab;

class PostController extends Controller
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
        $searchName = $request->input('search_name'); // Tên cần tìm kiếm
    
        // Xây dựng truy vấn dựa trên loại thẻ
        $query = topics::query();
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%')->orderBy('created_at', 'desc');
        }
    
        // Đếm tổng số dòng trong bảng dữ liệu
        $totalRows = $query->count();
    
        // Tính toán số lượng trang
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Tính toán vị trí bắt đầu của dữ liệu trong truy vấn
        $offset = ($currentPage - 1) * $itemsPerPage;
        $items = $query->offset($offset)->limit($itemsPerPage)->get();

    
        return view('admin.management.Post_manager', [
            'topics' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'searchName' => $searchName // Truyền giá trị loại thẻ đã lọc vào view
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


    public function handleCreatePostForm(Request $request)
    {
        $postTitle = $request->input('post_title');
        $postLevel = $request->input('post_level');
        $level = $request->input('level');
        return view('admin.function.topics.create_post', ['postTitle' => $postTitle, 'postLevel' => $postLevel, 'level' => $level]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
    
            $imagePath = public_path('img/' . $imageName);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Xóa ảnh cũ
            }
    
            $image->move(public_path('img'), $imageName);
        } else {
            $imageName = null;
        }
    
        $topics = topics::create([
            'name' => $request->input('post_title'),
            'category' => $request->input('post_level'),
            'level' => $request->input('level'),
            'created_at' => now(),
        ]);
    
        $postLevel = $request->input('post_level');
    
        if ($postLevel == 1) {
            $post = post::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'published_at' => now(),
                'topic_id' => $topics->id,
            ]);
    
            $imageModel = image::create([
                'image_content' => $post->id,
                'url' => $imageName,
                'created_at' => now(),
            ]);
    
            table_content::create([
                'post_id' => $post->id,
                'description' => $request->input('post_description'),
                'img_id' => $imageModel->id,
            ]);
    
            TranslateTextMK2::dispatch($post)->onQueue('default');
        } elseif ($postLevel == 2) {
            $post = post::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'published_at' => now(),
                'topic_id' => $topics->id,
            ]);
    
            $imageModel = image::create([
                'image_content' => $post->id,
                'url' => $imageName,
                'created_at' => now(),
            ]);
    
            vocab::create([
                'word' => $request->input('word'),
                'kanji' => $request->input('kanji'),
                'meaning' => $request->input('meaning'),
                'example' => $request->input('example'),
                'post_id' => $post->id,
                'img_id' => $imageModel->id,
                'created_at' => now(),
            ]);
        }
    
        return redirect()->route('topic.index')->with('success', 'Post created successfully.');
    }

    public function storeTranslate(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|max:10',
            'translations.*.field' => 'required|string',
            'translations.*.value' => 'required|string',
        ]);

        foreach ($request->input('translations') as $translation) {
            Translate::create([
                'model_type' => $request->input('model_type'),
                'model_id' => $request->input('model_id'),
                'locale' => $translation['locale'],
                'field' => $translation['field'],
                'value' => $translation['value'],
            ]);
        }

        return response()->json(['message' => 'Translations added successfully']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topic = topics::findOrFail($id);
        return view("admin.function.topics.edit_post", ['topics' => $topic]);
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
        $topics = topics::findOrFail($id);
        $topics->name = $request->input('topics_name');
        $topics->created_at = now();

        if ($topics->category == 1) {
            foreach ($topics->post as $post) {

                $post->title = $request->input('post_title');
                $post->description = $request->input('post_description');

                if ($post->content()->exists()) {
                    foreach ($post->content as $content) {
                        $content->description = $request->input('content_description');

                        if ($request->hasFile('item_image')) {
                            // Delete existing image
                            $imagePath = public_path('img/' . $content->image->url);
                            if (file_exists($imagePath)) {
                                unlink($imagePath); // Xóa ảnh cũ
                            }

                            // Upload new image
                            $image = $request->file('item_image');
                            $imageName = $image->getClientOriginalName();
                            $image->move(public_path('img/'), $imageName);
                            $content->image->url = $imageName;

                            $content->image->save();
                        }
                        $content->save();
                    }
                }
                $post->save();
            }
        } elseif ($topics->category == 2) {
            foreach ($topics->post as $post) {

                $post->title = $request->input('post_title');
                $post->description = $request->input('post_description');

                if ($post->vocab()->exists()) {
                    foreach ($post->vocab as $vocab) {
                        $vocab->word = $request->input('word');
                        $vocab->kanji = $request->input('kanji');
                        $vocab->meaning = $request->input('meaning');
                        $vocab->example = $request->input('example');

                        if ($request->hasFile('item_image')) {
                            // Delete existing image
                            $imagePath = public_path('img/' . $vocab->image->url);
                            if (file_exists($imagePath)) {
                                unlink($imagePath); // Xóa ảnh cũ
                            }

                            // Upload new image
                            $image = $request->file('item_image');
                            $imageName = $image->getClientOriginalName();
                            $image->move(public_path('img/'), $imageName);
                            $vocab->image->url = $imageName;

                            $vocab->image->save();
                        }
                        $vocab->save();
                    }
                }
                $post->save();
            }
        }

        $topics->save();

        return redirect()->route('topic.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = topics::findOrFail($id);

        // Kiểm tra nếu $topic có bất kỳ post nào không
        if ($topic->post()->exists()) {
            // Lấy tất cả các post thuộc topic và xóa chúng
            foreach ($topic->post as $post) {
                // Xóa tất cả các content liên quan đến post
                if ($post->content()->exists()) {
                    foreach ($post->content as $content) {
                        // Xóa ảnh liên quan đến content
                        $image = $content->image;
                        if ($image) {
                            $image->delete();
                        }
                        $content->delete();
                    }
                }
                // Xóa tất cả các vocab liên quan đến post
                if ($post->vocab()->exists()) {
                    foreach ($post->vocab as $vocab) {
                        // Xóa ảnh liên quan đến vocab
                        $image = $vocab->image;
                        if ($image) {
                            $image->delete();
                        }
                        $vocab->delete();
                    }
                }
                if ($post->comment()->exists()) {
                    foreach ($post->comment as $comment) {
                        $comment->delete();
                    }
                }
                // Xóa post
                $post->delete();
            }
        }

        // Xóa topic
        $topic->delete();

        return redirect()->route('topic.index')->with('success', 'Topic and related posts deleted successfully.');
    }
}
