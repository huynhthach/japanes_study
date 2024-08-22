<?php
namespace App\Jobs;

use App\Models\translate;
use App\Models\table_content;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class TranslateTextMK2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('TranslateTextMK2 job is running');

        $tr = new GoogleTranslate();
        $tr->setSource('auto'); // Dịch từ ngôn ngữ gốc
        $tr->setTarget('en'); // Ngôn ngữ đích

        // Dịch các trường trong bài post
        $fieldsToTranslate = ['title', 'description'];
        foreach ($fieldsToTranslate as $field) {
            if (isset($this->post->$field)) {
                $translation = $tr->translate($this->post->$field);
                translate::create([
                    'model_type' => 'posts',
                    'model_id' => $this->post->id,
                    'locale' => 'en',
                    'field' => $field,
                    'value' => $translation
                ]);
                Log::info("Translated {$field}: {$this->post->$field} => $translation");
            }
        }

        // Lấy nội dung từ bảng table_content và dịch
        $contents = table_content::where('post_id', $this->post->id)->get();
        Log::info('Number of contents found: ' . $contents->count());

        foreach ($contents as $content) {
            Log::info('Translating content id: ' . $content->id);
            $contentFieldsToTranslate = ['post_description']; // Change to 'description' if it's the correct field name
            foreach ($contentFieldsToTranslate as $contentField) {
                if (isset($content->$contentField)) {
                    Log::info("Translating table_content field {$contentField}: {$content->$contentField}");
                    $translation = $tr->translate($content->$contentField);
                    translate::create([
                        'model_type' => 'table_content',
                        'model_id' => $content->id,
                        'locale' => 'en',
                        'field' => $contentField,
                        'value' => $translation
                    ]);
                    Log::info("Translated table_content {$contentField}: {$content->$contentField} => $translation");
                } else {
                    Log::warning("Field {$contentField} is not set for content id: {$content->id}");
                }
            }
        }

        Log::info('TranslateTextMK2 job completed successfully');
    }
}


