<?php

namespace PortedCheese\BaseSettings\Events;

use App\Image;
use Illuminate\Queue\SerializesModels;

class PriorityUpdate
{
    use SerializesModels;

    public $table;
    public $ids;

    /**
     * Create a new event instance.
     *
     * @param string $table
     * @param array $ids
     */
    public function __construct(string $table, array $ids = [])
    {
        $this->table = $table;
        $this->ids = $ids;
    }
}
