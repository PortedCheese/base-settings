window.Lightbox = require('lightbox2');
window.Chosen = require('chosen-js');
window.Swal = require('sweetalert2');

(function ($) {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
        ckExample();
        ckDescription();
        customFileInput();
        activateChosen();
    });

    function activateChosen() {
        let $element = $('.chosen-select');
        if ($element.length) {
            $element.chosen();
        }
        $element = $('.chosen-select-deselect');
        if ($element.length) {
            $element.chosen({allow_single_deselect: true});
        }
    }

    function customFileInput() {
        $('.custom-file-input').each(function (index, element) {
            let $element = $(element);
            $element
                .on('change', function() {
                    let fileName = $(this)[0]
                        .files[0]
                        .name;
                    $(this)
                        .next('.custom-file-label')
                        .html(fileName);
                })
        });
    }

    function ckExample() {
        let $element = $('#ckExample');
        if ($element.length && typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('ckExample', {
                customConfig : '/config/ckEditorConfig.js'
            });
        }
    }

    function ckDescription() {
        let $element = $('#ckDescription');
        if ($element.length && typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('ckDescription', {
                customConfig : '/config/ckEditorConfig.js'
            });
        }
    }
})(jQuery);