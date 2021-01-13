<?php

namespace App\Jobs;

use App\Mail\ForgetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendForgetPasswordMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $link_reset_pass;
    public $customer;
    public $mail_to;

    public function __construct($link_reset_pass, $customer, $mail_to)
    {
        $this->link_reset_pass = $link_reset_pass;
        $this->customer = $customer;
        $this->mail_to = $mail_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new ForgetPasswordMail($this->link_reset_pass, $this->customer);
        Mail::to($this->mail_to)->send($email);
    }
}
