require('tinymce');
require("tinymce/themes/silver");
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/preview');
require('tinymce/plugins/code');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/table');
//require('tinymce/plugins/paste');
require('tinymce/plugins/code');
require('tinymce/plugins/wordcount');
require('tinymce/plugins/image');
require('tinymce/plugins/autolink');
require('tinymce/plugins/charmap');

(function ($) {
    $(document).ready(function(){
        initTiny();
    });
    var dialogBtn =  {
        title: 'Кнопка',
        body: {
            type: 'panel',
            items: [
                {
                    type: 'selectbox',
                    name: 'color',
                    label: 'Выберите цвет',
                    items: [
                        { value: 'primary', text: 'Основной' },
                        { value: 'secondary', text: 'Дополнительный' },
                        { value: 'warning', text: 'Уведомление' },
                        { value: 'danger', text: 'Предупреждение' },
                        { value: 'info', text: 'Информация' },
                        { value: 'dark', text: 'Темный' },
                    ]
                },
                {
                    type: 'input',
                    name: 'text',
                    label: 'Текст на кнопке'
                },
                {
                    type: 'input',
                    name: 'link',
                    label: 'Сcылка с кнопки'
                },
                {
                    type: 'input',
                    name: 'toggle',
                    label: 'Открыть окно'
                }
            ]
        },
        buttons: [
            {
                type: 'cancel',
                name: 'closeButton',
                text: 'Cancel'
            },
            {
                type: 'submit',
                name: 'submitButton',
                text: 'Добавить кнопку',
                primary: true
            }
        ],
        initialData: {
            text: '',
            link: '',
            toggle: '',
            color: 'primary',
        },
        onSubmit: function (api) {
            var data = api.getData();
            var link = data.link;
            var toggle = data.toggle;

            if (link.length > 0) {
                tinymce.activeEditor.execCommand('mceInsertContent', false,
                    '<a class="btn btn-'+ data.color +'" href="'+ link+'">' + data.text + '</a>'
                );
            }
            else
            if (toggle.length >0) {
                tinymce.activeEditor.execCommand('mceInsertContent', false,
                    '<a class="btn btn-'+ data.color +'" href="#" data-bs-toggle="modal" data-bd-target="'+ toggle +'">' + data.text + '</a>'
                );
            }
            api.close();
        }
    };
    function initTiny() {
        let $selector = $(".tiny");
        if (! $selector.length) {
            return;
        }

        tinymce.init({
            selector: ".tiny",
            sandbox_iframes: false,
            license_key: 'gpl',
            height: 300,
            menubar: false,
            plugins: [
                'lists', 'link','image','preview','code','fullscreen','table','code','wordcount'
            ],
            toolbar: [
                'undo redo | removeformat code fullscreen',
                'styles | bold italic link | bullist numlist outdent indent | image table | dialog-btn'],
            style_formats: [
                {title: 'Текст', items: [
                        { title: 'Обычный', block: 'p'},
                        { title: 'Маленький', block: 'small'},
                        { title: 'Цитата', block: 'blockquote'},
                    ]},
                {title: 'Заголовки', items: [
                        { title: 'H2', block: 'h2'},
                        { title: 'H3', block: 'h3'},
                        { title: 'H4', block: 'h4'},
                        { title: 'H5', block: 'h5'},
                        { title: 'H6', block: 'h6'},
                    ]},
                { title: 'Выделить цветом блок', items: [
                        {title : 'Типовой цвет', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-body'},
                        {title : 'Основной цвет', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-primary'},
                        {title : 'Дополнительный цвет', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-secondary'},
                        {title : 'Уведомление', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-warning'},
                        {title : 'Предупреждение', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-danger'},
                        {title : 'Инфо', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-info'},
                        {title : 'Темный', selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,span,strong,em', classes : 'text-dark'},
                    ]},

                { title: 'Форматы изображений', items: [
                        { title: 'Изображение слева', selector: 'img', styles: { 'float': 'left', 'margin': '0 10px 0 10px' } },
                        { title: 'Изображение справа', selector: 'img', styles: { 'float': 'right', 'margin': '0 0 10px 10px' } },
                    ] },
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css',
                '/css/admin.css'
            ],
            setup: function (editor) {

                editor.ui.registry.addButton('dialog-btn', {
                    icon: 'color-swatch',
                    onAction: function () {
                        editor.windowManager.open(dialogBtn)
                    }
                })
            },
            //file_picker_callback : elFinderBrowser
        });
    }

    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
            e.stopImmediatePropagation();
        }
    });


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
