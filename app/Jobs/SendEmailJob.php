<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmails;
use Exception;
use Illuminate\Support\Facades\Log;

class SendEmailJob extends Job
{

    protected $details; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendEmails($this->details);
        Mail::to($this->details['email'])->send($email);
    }
}
