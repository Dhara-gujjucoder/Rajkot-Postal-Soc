<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanMail extends Mailable
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
        return $this->subject('LOAN INQUIRY')
                    ->view('email.loan_email',$this->data);
    }
}
