<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CheckoutMail;
use Illuminate\Support\Facades\Mail;

class SendCheckoutMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $email_address;
    public $order;
    public $shipping;
    public $cart;


    public function __construct($email_address, $order, $shipping, $cart)
    {
        $this->email_address = $email_address;
        $this->order = $order;
        $this->shipping = $shipping;
        $this->cart = $cart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new CheckoutMail($this->order, $this->shipping, $this->cart);
        Mail::to($this->email_address)->send($email);
    }
}
