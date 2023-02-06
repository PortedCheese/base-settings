
<picture>
    @foreach ($grid as $gridTemplate => $width)
        <source srcset="{{ route('imagecache', [
                                'template' => $gridTemplate,
                                'filename' => $image->file_name
                            ]) }}"
                media="(min-width: {{ $width }}px)">
    @endforeach
    <img @if (empty ($detectIe))
         data-src="{{ route('imagecache', [
                            'template' => $template,
                            'filename' => $image->file_name
                        ]) }}"
         @else
         src="{{ route('imagecache', [
                            'template' => $template,
                            'filename' => $image->file_name
                        ]) }}"
         @endif

         class="{{ empty($imgClass) ? 'img-thumbnail lazyload' : 'lazyload '.$imgClass }}"
         alt="{{ $image->name }}">
</picture>