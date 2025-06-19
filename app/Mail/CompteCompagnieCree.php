<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompteCompagnieCree extends Mailable
{
    use Queueable, SerializesModels;

    public $compagnie;
    public $motDePasse;

    public function __construct($compagnie, $motDePasse)
    {
        $this->compagnie = $compagnie;
        $this->motDePasse = $motDePasse;
    }

    public function build()
    {
        return $this->subject('Création de votre compte Compagnie')
            ->view('email.reponse')
            ->with([
                'compagnie' => $this->compagnie,
                'motDePasse' => $this->motDePasse,
            ]);
    }
}
