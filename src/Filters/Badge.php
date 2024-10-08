<?php

namespace PortedCheese\BaseSettings\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Badge implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(45, 45);
    }
}
