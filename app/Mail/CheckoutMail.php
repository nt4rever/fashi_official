<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $order;
    public $shipping;
    public $cart;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $shipping, $cart)
    {
        $this->order = $order;
        $this->shipping = $shipping;
        $this->cart = $cart;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS', 'tan@nt4rever.tech'))->subject("Fashi shop")
            ->view('admin.mail.checkout');
        // ->text('admin.text_mail')
        // ->with(
        //     [
        //         'testVarOne' => '1',
        //         'testVarTwo' => '2',
        //     ]
        //     );
        // ->attach(public_path('/images') . '/demo.jpg', [
        //     'as' => 'demo.jpg',
        //     'mime' => 'image/jpeg',
        // ]);
    }
}
