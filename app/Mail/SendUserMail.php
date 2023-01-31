<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserMail extends Mailable 
{
    use Queueable, SerializesModels;

    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('sendemail');
            $mail->subject($this->details['subject']);
            if($this->details['attechment']){
                $mail->attach(storage_path('app/public/'.$this->details['attechment']));
            }
            if($this->details['from']){
                $mail->from($this->details['from']);
            }
            if($this->details['cc']){
                $cc = explode(',',$this->details['cc']);
                $mail->cc($cc);
            }
            if($this->details['bcc']){
                $bcc = explode(',',$this->details['bcc']);
                $mail->bcc($bcc);
            }
            //dd($mail);
        return $mail;
    }
}
