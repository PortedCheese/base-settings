<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class RedirectController extends Controller
{

    /**
     * Redirect to url
     *
     * @param $url
     * @return \Illuminate\Http\RedirectResponse
     */

    public function to($url) {
        return Redirect::away('https://'.$url);
    }
}
