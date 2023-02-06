window.Lightbox = require('lightbox2');
window.Chosen = require('chosen-js');
window.noUiSlider = require('nouislider');
// import a plugin
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';

(function ($) {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
        customFileInput();
        activateChosen();
        rangeSlider();
        ajaxLogin();
    });

    function ajaxLogin() {
        let $forms = $('.ajax-login-form');
        $forms.each(function (index, element) {
            $(element).on('submit', function (event) {
                event.preventDefault();

                let $form = $(event.target);
                let $submit = $form.find("input[type='submit']");
                if (! $submit.length) {
                    $submit = $form.find("button[type='submit']");
                }
                let formData = new FormData($form[0]);

                $submit.attr('disabled', 'disabled');
                $submit.append("<i class=\"loader fas fa-spinner fa-spin\"></i>");
                $form.find('.invalid-feedback').each(function (inx, el) {
                    $(el).parent().find('input').removeClass('is-invalid');
                    $(el).remove();
                });

                axios
                    .post($form.attr('action'), formData)
                    .then(response => {
                        document.location.reload(true);
                    })
                    .catch(error => {
                        let data = error.response.data;
                        for (let item in data.errors) {
                            let $input = $form.find("input[name='" + item + "']");
                            let $parent = $input.parent().append("<span class=\"invalid-feedback\" role=\"alert\"></span>");
                            let $errorBlock = $parent.find('.invalid-feedback');
                            $input.toggleClass('is-invalid');
                            for (index in data.errors[item]) {
                                if (data.errors[item].hasOwnProperty(index)) {
                                    $errorBlock.append("<strong>" + data.errors[item][index] + "</strong>");
                                }
                            }
                        }
                    })
                    .finally(() => {
                        $submit.removeAttr('disabled');
                        $submit.find(".loader").remove();
                    });
            });
        });
    }

    function rangeSlider() {
        $(".steps-slider-cover").each(function (index, element) {
            let stepsSlider = $(element).find('.steps-slider')[0];
            let $from = $(element).find('.from-input');
            let $to = $(element).find('.to-input');
            let inputs = [$from[0], $to[0]];
            let min = parseInt($from.attr('data-value'));
            let max = parseInt($to.attr('data-value'));
            let range = [parseInt($from.attr('data-init')), parseInt($to.attr('data-init'))];
            let step = parseInt($(element).attr('data-step'));
            if (isNaN(step)) {
                step = 10;
            }

            noUiSlider.create(stepsSlider, {
                start: range,
                connect: true,
                step: step,
                range: {
                    'min': min,
                    'max': max
                },
                format: {
                    from: function(value) {
                        return parseInt(value);
                    },
                    to: function(value) {
                        return parseInt(value);
                    }
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
})(jQuery);