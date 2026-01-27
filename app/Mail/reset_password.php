<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class reset_password extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->subject($this->data['subject'])
        ->from('noreply@boat.orangeworkshop.info')
        ->to('kittiwatzero@gmail.com')
       ->markdown('emails.reset_password')->with([
            'subject'=>$this->data['subject'],
            'name' => $this->data['name'],
            'username' => $this->data['username'],
            'url' => $this->data['url'],

       ]);

    }

}
