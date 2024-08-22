<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\TranslateText;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{

    public function index(){
        return view('translations.translationsindex');
    }

    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);
    
        $currentUser = Auth::user();
        $text = $request->input('text');
        $adminEmail = 'huynhlong2314@gmail.com'; // Email of the admin
        if(Auth::check()){
            $userEmail = $currentUser->email; // Email of the logged-in user
        }else{
            $userEmail = $request->input('text'); // Email of the logged-in user
        }
        // Dispatch job with the text, admin's email, and logged-in user's email
        TranslateText::dispatch($text, $adminEmail, $userEmail);
    
        return redirect()->route('user.about_us')->with('success', 'Your text has been submitted. You will receive an email shortly.');
    }
    

    public function show($cacheKey)
    {
        $translation = Cache::get($cacheKey);

        if (!$translation) {
            return abort(404, 'Translation not found.');
        }

        return view('translations.show', compact('translation'));
    }
}

