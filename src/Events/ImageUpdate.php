<?php

namespace PortedCheese\BaseSettings\Events;

use App\Image;
use Illuminate\Queue\SerializesModels;

class ImageUpdate
{
    use SerializesModels;

    public $image;
    public $morph;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
        $this->morph = $image->imageable;
    }
}
