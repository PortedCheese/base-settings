<?php

namespace PortedCheese\BaseSettings\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserUpdate
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
