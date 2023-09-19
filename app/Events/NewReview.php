<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewReview
{
    use SerializesModels;

    public $review;

    /**
     * Create a new event instance.
     */
    public function __construct($review)
    {
        $this->review = $review;
    }
}
