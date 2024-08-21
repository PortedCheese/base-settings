<?php

namespace PortedCheese\BaseSettings\Filters;

use Intervention\Image\Facades\Image;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image as File;

class XlGrid6 implements FilterInterface {

    public function applyFilter(File $image)
    {
        return $image
            ->fit(544, 245);
    }
}