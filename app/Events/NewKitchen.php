<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewKitchen
{
    use SerializesModels;

    public $kitchen;

    /**
     * Create a new event instance.
     */
    public function __construct($kitchen)
    {
        $this->kitchen = $kitchen;
    }
}
