<figure class="figure">
    <a href="{{ route('imagecache', ['template' => 'original', 'filename' => $image->file_name]) }}"
       @empty($lightbox)
       data-lightbox="image-{{ $image->id }}"
       @endempty
       @isset($lightbox)
       data-lightbox="{{ $lightbox }}"
       @endisset>
        <img src="{{ route('imagecache', [
                                'template' => $template,
                                'filename' => $image->file_name
                            ]) }}"
             class="img-thumbnail"
             alt="{{ $image->name }}">
    </a>
</figure>