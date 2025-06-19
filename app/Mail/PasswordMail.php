<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * ========== Constructeur ==========
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * ========== Construction du message ==========
     */
    public function build()
    {
        return $this->subject('Réinitialisation de votre mot de passe')
            ->view('emails.reinitialiser_pass')
            ->with([
                'token' => $this->token,
            ]);
    }
}
