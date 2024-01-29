<figure class="figure">
    @php($imgClass = empty($imgClass) ? "img-thumbnail" : $imgClass)
    <a href="{{ route('image-filter', ['template' => 'original', 'filename' => $image->file_name]) }}"
       data-lightbox="{{ ! empty($lightbox) ? $lightbox : "image-{$image->id}" }}">
        @if(! empty($grid))
            @pic([
                'image' => $image,
                'template' => $template,
                'grid' => $grid,
                'imgClass' => $imgClass,
            ])
        @else
            <img src="{{ route('image-filter', [
                        'template' => $template,
                        'filename' => $image->file_name
                    ]) }}"
                 class="{{ $imgClass }}"
                 alt="{{ $image->name }}">
        @endif
    </a>
</figure>