<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewReservation
{
    use SerializesModels;

    public $reservation;

    /**
     * Create a new event instance.
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }
}
