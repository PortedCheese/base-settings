window.Lightbox = require('lightbox2');
window.Chosen = require('chosen-js');
window.Swal = require('sweetalert2-neutral');

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
                    let files = $(this)[0].files;
                    let names = [];
                    for (let item in files) {
                        if (files.hasOwnProperty(item)) {
                            names.push(files[item].name);
                        }
                    }
                    let fileName = names.join(", ");
                    $(this)
                        .next('.custom-file-label')
                        .html(fileName);
                })
        });
    }

    function ckExample() {
        let $element = $('#ckExample');
        if ($element.length) {
            $element.addClass("tiny");
        }
        // if ($element.length && typeof CKEDITOR !== 'undefined') {
        //     CKEDITOR.replace('ckExample', {
        //         customConfig : '/config/ckEditorConfig.js'
        //     });
        // }
    }

    function ckDescription() {
        let $element = $('#ckDescription');
        if ($element.length) {
            $element.addClass("tiny");
        }
        // if ($element.length && typeof CKEDITOR !== 'undefined') {
        //     CKEDITOR.replace('ckDescription', {
        //         customConfig : '/config/ckEditorConfig.js'
        //     });
        // }
    }
})(jQuery);

require("./initTynyMCE");