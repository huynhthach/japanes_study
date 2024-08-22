<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TranslationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $text;
    public $userEmail;

    public function __construct($text, $userEmail)
    {
        $this->text = $text;
        $this->userEmail = $userEmail;
    }

    public function build()
    {
        return $this->view('emails.translation_completed')
                    ->with([
                        'text' => $this->text,
                        'userEmail' => $this->userEmail,
                    ]);
    }
}



