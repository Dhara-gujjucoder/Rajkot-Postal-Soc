<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->data = $details;
    }

    public function build()
    {
        // dd($this->data);
        return $this->subject('Contact Request From Someonetotalkto')
                    ->view('email.contactus_email',$this->data);
    }
}
