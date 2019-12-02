<div class="row justify-content-around gallery">
    @foreach ($gallery as $image)
        <div class="col-6 col-lg-3 mt-2 text-center">
            @php($grid = empty($grid) ? [] : $grid)
            @img([
                'image' => $image,
                'template' => $template,
                'lightbox' => $lightbox,
                'imgClass' => empty($imgClass) ? null : $imgClass,
                'grid' => $grid,
            ])
        </div>
    @endforeach
</div>