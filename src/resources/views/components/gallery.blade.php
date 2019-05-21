<div class="row justify-content-around gallery news-gallery">
    @foreach ($gallery as $image)
        <div class="col-12 col-sm-4 col-lg-3 mt-2 text-center">
            @image(['image' => $image, 'template' => $template, 'lightbox' => $lightbox])@endimage
        </div>
    @endforeach
</div>