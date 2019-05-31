<figure class="figure">
    @php($imgClass = empty($imgClass) ? "img-thumbnail" : $imgClass)
    <a href="{{ route('imagecache', ['template' => 'original', 'filename' => $image->file_name]) }}"
       @empty($lightbox)
       data-lightbox="image-{{ $image->id }}"
       @endempty
       @isset($lightbox)
       data-lightbox="{{ $lightbox }}"
       @endisset>
        @if(! empty($grid))
            @picture([
                'image' => $image,
                'template' => $template,
                'grid' => $grid,
                'imgClass' => $imgClass,
            ])@endpicture
        @else
            <img src="{{ route('imagecache', [
                        'template' => $template,
                        'filename' => $image->file_name
                    ]) }}"
                 class="{{ $imgClass }}"
                 alt="{{ $image->name }}">
        @endif
    </a>
</figure>