<?php

namespace Travelestt\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailResponses extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $demo;
    public $title;
    public $logo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $title, $logo = null)
    {
        $this->demo = $message;
        $this->title = $title;
        $this->logo = $logo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@travelestt.com')
                    ->view('email.message')
                    ->subject($this->title);
    }
}
