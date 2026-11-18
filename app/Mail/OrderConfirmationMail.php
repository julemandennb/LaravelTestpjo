<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
         public $order
    )
    {}

    public function build()
    {
        return $this
            ->subject('Your Order Confirmation')
            ->markdown('emails.orders.confirmation');
    }
}
