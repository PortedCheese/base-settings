<?php

namespace PortedCheese\BaseSettings\Filters;

use Intervention\Image\Facades\Image;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image as File;

class SmGrid6 implements FilterInterface {

    public function applyFilter(File $image)
    {
        return $image
            ->fit(270, 270);
    }
}