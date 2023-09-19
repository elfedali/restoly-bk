<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewPayment
{
    use SerializesModels;

    public $payment;

    /**
     * Create a new event instance.
     */
    public function __construct($payment)
    {
        $this->payment = $payment;
    }
}
