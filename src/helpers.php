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

if (!function_exists('datehelper')) {
    function datehelper() {
        return app(\PortedCheese\BaseSettings\Http\Helpers\DateHelper::class);
    }
}