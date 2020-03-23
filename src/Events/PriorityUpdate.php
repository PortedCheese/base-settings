<?php

namespace PortedCheese\BaseSettings\Events;

use App\Image;
use Illuminate\Queue\SerializesModels;

class PriorityUpdate
{
    use SerializesModels;

    public $table;

    /**
     * Create a new event instance.
     *
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }
}
