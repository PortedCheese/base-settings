<?php

if (!function_exists('siteconf')) {
    /**
     * Get the SiteConfig instance
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    function siteconf()
    {
        return app("siteconf");
    }
}

if (!function_exists('datehelper')) {
    /**
     * @return \PortedCheese\BaseSettings\Helpers\DateHelper
     */
    function datehelper() {
        return app("datehelper");
    }
}

if (! function_exists("base_config")) {
    /**
     * @return \PortedCheese\BaseSettings\Helpers\ConfigManager
     */
    function base_config() {
        return app("base-config");
    }
}