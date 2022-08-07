<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ConfirmOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $shipping;
    public $file_attach;

    public function __construct($shipping, $file_attach)
    {
        $this->shipping = $shipping;
        $this->file_attach = $file_attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */


    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'tan@nt4rever.tech'))->subject("Fashi shop - Xác nhận đơn hàng")
            ->view('admin.mail.confirm')->attach($this->file_attach);
    }
}
