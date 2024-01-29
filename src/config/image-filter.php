<?php

return array(

    'filterFacade' => \PortedCheese\BaseSettings\Helpers\FilterActionsManager::class,

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic image filter manipulation.
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/filter"
    |
    */

    'url' => 'filter',


    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */

    'templates' => array(),

    /*
   |--------------------------------------------------------------------------
   | Storage paths
   |--------------------------------------------------------------------------
   |
   | The following paths will be searched for the image filename, submited
   | by URI.
   |
   | Define as many directories as you like.
   |
   */

    'paths' => array(),

    /*
    |--------------------------------------------------------------------------
    | Image filter Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the route.
    |
    */

//    'lifetime' => 1,
    'lifetime' => 0//43200,

);
