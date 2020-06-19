<?php

if (!function_exists('siteconf')) {
    /**
     * Get the SiteConfig instance
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function siteconf()
    {
        return app(\PortedCheese\BaseSettings\Helpers\SiteConfig::class);
    }
}

if (!function_exists('datehelper')) {
    function datehelper() {
        return app(\PortedCheese\BaseSettings\Helpers\DateHelper::class);
    }
}

if (! function_exists("base_config")) {
    function base_config() {
        return app("base-config");
    }
}