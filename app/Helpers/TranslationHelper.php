<?php
namespace App\Helpers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Session;

class TranslationHelper
{
    public static function translate($text, $targetLang)
    {
        $sessionKey = md5($text . $targetLang);

        // Kiểm tra xem bản dịch đã tồn tại trong session chưa
        if (Session::has($sessionKey)) {
            return Session::get($sessionKey);
        }

        $tr = new GoogleTranslate();
        $tr->setSource('auto'); // Dịch từ ngôn ngữ gốc
        $tr->setTarget($targetLang); // Ngôn ngữ đích
        $translatedText = $tr->translate($text);

        // Lưu bản dịch vào session
        Session::put($sessionKey, $translatedText);

        return $translatedText;
    }
}