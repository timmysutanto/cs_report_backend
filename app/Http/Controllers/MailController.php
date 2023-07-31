<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function testingcoba()
    {
        return view('mail');
    }

    public function testEmail()
    {

        $data = array('name' => "John");

        Mail::send('mail', $data, function ($message) {
            $message->to('wijayajohan215@gmail.com', 'pals')->subject('Test Mail from Johan');
            $message->from('lirhayskidis24@gmail.com');
        });

        echo "Email Sent. Check your inbox.";
        echo "meow";
    }
}
