<figure class="figure">
    @php($imgClass = empty($imgClass) ? "lazyload img-thumbnail" : 'lazyload '.$imgClass)
    <a href="{{ route('image-filter', ['template' => 'original', 'filename' => $image->file_name]) }}"
       data-lightbox="{{ ! empty($lightbox) ? $lightbox : "image-{$image->id}" }}">
        @if(! empty($grid))
            @picLazy([
                'image' => $image,
                'template' => $template,
                'grid' => $grid,
                'imgClass' => $imgClass,
            ])
        @else
            <img @if (empty ($detectIe))
                 data-src="{{ route('image-filter', [
                        'template' => $template,
                        'filename' => $image->file_name
                    ]) }}"
                 @else
                 src="{{ route('image-filter', [
                        'template' => $template,
                        'filename' => $image->file_name
                    ]) }}"
                 @endif
                 class="{{ $imgClass }}"
                 alt="{{ $image->name }}">
        @endif
    </a>
</figure>
