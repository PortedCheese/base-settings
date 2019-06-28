<?php

if (!function_exists('siteconf')) {
    /**
     * Get the SiteConfig instance
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function siteconf()
    {
        return app(\PortedCheese\BaseSettings\Http\Helpers\SiteConfig::class);
    }
}