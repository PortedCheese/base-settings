require('tinymce');
require("tinymce/themes/silver");
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/preview');
require('tinymce/plugins/code');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/table');
require('tinymce/plugins/paste');
require('tinymce/plugins/code');
require('tinymce/plugins/wordcount');
require('tinymce/plugins/image');
require('tinymce/plugins/autolink');
require('tinymce/plugins/charmap');

(function ($) {
    $(document).ready(function(){
        initTiny();
    });

    function initTiny() {
        let $selector = $(".tiny");
        if (! $selector.length) {
            return;
        }
        tinymce.init({
            selector: ".tiny",
            height: 300,
            menubar: false,
            plugins: [
                'lists link image preview code fullscreen table paste code wordcount'
            ],
            toolbar1: "undo redo | removeformat code fullscreen",
            toolbar2: 'styleselect | bold italic link | bullist numlist outdent indent | image table',
            style_formats: [
                { title: 'Обычный', block: 'p'},
                { title: 'Заголовки' },
                { title: 'Заголовок 2', block: 'h2'},
                { title: 'Заголовок 3', block: 'h3'},
                { title: 'Заголовок 4', block: 'h4'},
                { title: 'Форматы изображений' },
                { title: 'Изображение слева', selector: 'img', styles: { 'float': 'left', 'margin': '0 10px 0 10px' } },
                { title: 'Изображение справа', selector: 'img', styles: { 'float': 'right', 'margin': '0 0 10px 10px' } },
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            file_picker_callback : elFinderBrowser
        });
    }

    function elFinderBrowser (callback, value, meta) {
        tinymce.activeEditor.windowManager.openUrl({
            title: 'File Manager',
            url: "/elfinder/tinymce5",
            /**
             * On message will be triggered by the child window
             *
             * @param dialogApi
             * @param details
             * @see https://www.tiny.cloud/docs/ui-components/urldialog/#configurationoptions
             */
            onMessage: function (dialogApi, details) {
                if (details.mceAction === 'fileSelected') {
                    const file = details.data.file;

                    // Make file info
                    const info = file.name;

                    // Provide file and text for the link dialog
                    if (meta.filetype === 'file') {
                        callback(file.url, {text: info, title: info});
                    }

                    // Provide image and alt text for the image dialog
                    if (meta.filetype === 'image') {
                        callback(file.url, {alt: info});
                    }

                    // Provide alternative source and posted for the media dialog
                    if (meta.filetype === 'media') {
                        callback(file.url);
                    }

                    dialogApi.close();
                }
            }
        });
    }
})(jQuery);
