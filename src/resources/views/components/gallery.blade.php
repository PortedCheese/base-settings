<div class="row justify-content-around gallery news-gallery">
    @foreach ($gallery as $image)
        <div class="col-6 col-lg-3 mt-2 text-center">
            @php($grid = empty($grid) ? [] : $grid)
            @image([
                'image' => $image,
                'template' => $template,
                'lightbox' => $lightbox,
                'imgClass' => empty($imgClass) ? null : $imgClass,
                'grid' => $grid,
            ])@endimage
        </div>
    @endforeach
</div>