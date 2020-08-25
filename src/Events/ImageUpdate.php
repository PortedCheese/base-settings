<?php

namespace PortedCheese\BaseSettings\Events;

use App\Image;
use Illuminate\Queue\SerializesModels;

class ImageUpdate
{
    use SerializesModels;

    public $image;
    public $morph;
    public $action;

    /**
     * Create a new event instance.
     *
     * ImageUpdate constructor.
     * @param Image $image
     * @param string $action
     */
    public function __construct(Image $image, string $action = "undefined")
    {
        $this->image = $image;
        $this->morph = $image->imageable;
        $this->action = $action;
    }
}
