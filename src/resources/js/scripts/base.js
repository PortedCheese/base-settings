(function ($) {
    $(document).ready(function(){
        ckExample();
        ckDescription();
        customFileInput();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
        activateChosen();
        rangeSlider();
    });

    function rangeSlider() {
        $(".steps-slider-cover").each(function (index, element) {
            let stepsSlider = $(element).find('.steps-slider')[0];
            let $from = $(element).find('.from-input');
            let $to = $(element).find('.to-input');
            let inputs = [$from[0], $to[0]];
            let min = parseInt($from.attr('data-value'));
            let max = parseInt($to.attr('data-value'));
            let range = [parseInt($from.attr('data-init')), parseInt($to.attr('data-init'))];

            noUiSlider.create(stepsSlider, {
                start: range,
                connect: true,
                padding: 10,
                step: 10,
                range: {
                    'min': min - 10,
                    'max': max + 10
                }
            });

            stepsSlider.noUiSlider.on('update', function (values, handle) {
                inputs[handle].value = values[handle];
            });

            $from[0].addEventListener('change', function () {
                stepsSlider.noUiSlider.set([this.value, null]);
            });

            $to[0].addEventListener('change', function () {
                stepsSlider.noUiSlider.set([null, this.value]);
            });
        });
    }

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
                customConfig : '/config/ckeditor_config.js'
            });
        }
    }

    function ckDescription() {
        let $element = $('#ckDescription');
        if ($element.length && typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('ckDescription', {
                customConfig : '/config/ckeditor_config.js'
            });
        }
    }
})(jQuery);