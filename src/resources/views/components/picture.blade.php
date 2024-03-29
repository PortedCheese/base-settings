<picture>
    @foreach ($grid as $gridTemplate => $width)
        <source srcset="{{ route('image-filter', [
                                'template' => $gridTemplate,
                                'filename' => $image->file_name
                            ]) }}"
                media="(min-width: {{ $width }}px)">
    @endforeach
    <img src="{{ route('image-filter', [
                            'template' => $template,
                            'filename' => $image->file_name
                        ]) }}"
         class="{{ empty($imgClass) ? 'img-thumbnail' : $imgClass }}"
         alt="{{ $image->name }}">
</picture>