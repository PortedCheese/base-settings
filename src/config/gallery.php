<?php

return array(
    /*
    |-------------------------------------
    | Доступные модели
    |-------------------------------------
    |
    | Можно перечислить модели которые обладают галлерей,
    | при этом у модели должен быть метод images.
    |
    | public function images() {
    |   return $this->morphMany('App\Image', 'imageable');
    | }
    |
    */
    'models' => array(
        'user' => 'App\User',
    ),
);