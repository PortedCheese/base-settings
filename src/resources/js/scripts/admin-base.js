window.Lightbox = require('lightbox2');
window.Chosen = require('chosen-js');
window.Swal = require('sweetalert2-neutral');
require("./initTynyMCE");

document.addEventListener('DOMContentLoaded', function(){
    customFileInput();
    ckExample();
    ckDescription();

    function customFileInput() {
        document.querySelectorAll('.custom-file-input').forEach(function(element, index) {
            element.addEventListener("change", (event) => {
                let files = element.files;
                let names = [];
                for (let item in files) {
                    if (files.hasOwnProperty(item)) {
                        names.push(files[item].name);
                    }
                }
                let fileName = names.join(", ");
                let label = element.nextElementSibling;
                if (label.classList.contains('custom-file-label')){
                    label.insertAdjacentHTML('beforeend',fileName);
                }
            })
        });
    }

    function ckExample() {

        let element = document.getElementById('#ckExample');
        if (element) {
            element.classList.add("tiny");
        }
        // if (element.length && typeof CKEDITOR !== 'undefined') {
        //     CKEDITOR.replace('ckExample', {
        //         customConfig : '/config/ckEditorConfig.js'
        //     });
        // }
    }

    function ckDescription() {
        let element = document.getElementById('#ckDescription');
        if (element) {
            element.classList.add("tiny");
        }
        // if (element.length && typeof CKEDITOR !== 'undefined') {
        //     CKEDITOR.replace('ckDescription', {
        //         customConfig : '/config/ckEditorConfig.js'
        //     });
        // }
    }

});

(function ($) {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
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


})(jQuery);

