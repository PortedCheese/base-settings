<?php

namespace PortedCheese\BaseSettings\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class WidenLogo implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->widen(198);
    }
}
