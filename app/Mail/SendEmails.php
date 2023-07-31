<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

use Illuminate\Queue\SerializesModels;

class SendEmails extends Mailable
{
    use Queueable, SerializesModels;

    protected $data; 
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
        $ticket_number = $this->data['ur_number'];
        $username = $this->data['username'];
        $user = $this->data['user']; 
        $created = $this->data['created'];
        $priority = $this->data['priority']; 
        $description = $this->data['description'];
        $text_one = $this->data['text_one'];
        $text_two = $this->data['text_two'];
        $subjects = $this->data['subjects'];
        return $this->view('emails.email', compact('ticket_number', 'username', 'user', 'created', 'priority', 'description', 'text_one', 'text_two'))->subject($subjects);
    }
}
