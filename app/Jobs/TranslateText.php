<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\TranslationCompleted;

class TranslateText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $text;
    protected $adminEmail;
    protected $userEmail;

    public function __construct($text, $adminEmail, $userEmail)
    {
        $this->text = $text;
        $this->adminEmail = $adminEmail;
        $this->userEmail = $userEmail;
    }

    public function handle()
    {
        // Send email notification with the translation text and the logged-in user's email
        Mail::to($this->adminEmail)->send(new TranslationCompleted($this->text, $this->userEmail));
    }
}
