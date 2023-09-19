<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class NewContact
{
    use SerializesModels;

    public $contact;

    /**
     * Create a new event instance.
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }
}
