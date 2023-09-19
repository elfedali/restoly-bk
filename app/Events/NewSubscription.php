<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewSubscription
{
    use SerializesModels;

    public $subscription;

    /**
     * Create a new event instance.
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }
}
