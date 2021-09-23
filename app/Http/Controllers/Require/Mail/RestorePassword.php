<?php

namespace App\Http\Controllers\Require\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestorePassword extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Restablecer contraseña';
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $url)
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('restorePassword',  ['url' => $this->url]);
    }
}
