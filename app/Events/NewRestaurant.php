<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewRestaurant
{
    use SerializesModels;

    public $restaurant;

    /**
     * Create a new event instance.
     */
    public function __construct($restaurant)
    {
        $this->restaurant = $restaurant;
    }
}
