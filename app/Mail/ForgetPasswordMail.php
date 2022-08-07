<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $link_reset_pass;
    public $customer;

    public function __construct($link_reset_pass, $customer)
    {
        $this->link_reset_pass = $link_reset_pass;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'tan@nt4rever.tech'))->subject("Fashi shop - Đổi mật khẩu")
            ->view('admin.mail.forget_password');
    }
}
