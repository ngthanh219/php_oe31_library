<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $user, $request;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('author.notifi'))
            ->markdown('emails.notification.notification')
            ->with([
                'user' => $this->user,
                'request' => $this->request,
                'url' => route('home'),
            ]);
    }
}
