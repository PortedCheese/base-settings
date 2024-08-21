window.Lightbox = require('lightbox2');
window.Chosen = require('chosen-js');
window.noUiSlider = require('nouislider');
// import a plugin
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', function(){
    jsLogin();
    rangeSlider();
    customFileInput();

    function jsLogin(){
        document.querySelectorAll('.ajax-login-form').forEach(function(element, index) {
            document.addEventListener("submit",(event) => {
                event.preventDefault();
                let form = event.target;
                let submit = form.querySelectorAll("input[type='submit']");
                if (! submit.length){
                    submit = form.querySelectorAll("button[type='submit']");
                }
                let formData = new FormData(form);
                submit[0].setAttribute('disabled','disabled');
                submit[0].insertAdjacentHTML('beforeend','<i class=\"loader fas fa-spinner fa-spin\"></i>');
                form.querySelectorAll('.invalid-feedback').forEach(function (el, inx) {
                    el.parentElement.querySelector('input').classList.remove('is-invalid');
                    el.remove();
                });
                axios
                    .post(form.getAttribute('action'), formData)
                    .then(response => {
                        document.location.reload(true);
                    })
                    .catch(error => {
                        let data = error.response.data;
                        for (let item in data.errors) {
                            let input = form.querySelector("input[name='" + item + "']");
                            let parent = input.parentElement;
                            parent.insertAdjacentHTML('beforeend',"<span class=\"invalid-feedback\" role=\"alert\"></span>");
                            let errorBlock = parent.querySelectorAll('.invalid-feedback');
                            input.classList.toggle('is-invalid');
                            for (index in data.errors[item]) {
                                if (data.errors[item].hasOwnProperty(index)) {
                                    errorBlock[0].insertAdjacentHTML('beforeend',"<strong>" + data.errors[item][index] + "</strong>");
                                }
                            }
                        }
                    })
                    .finally(() => {
                        submit[0].removeAttribute('disabled');
                        submit[0].querySelector(".loader").remove();
                    });
            });
        });
    }

    function rangeSlider(){
        document.querySelectorAll('.steps-slider-cover').forEach(function(element, index) {
            let stepsSlider = element.querySelectorAll('.steps-slider')[0];
            let from = element.querySelectorAll('.from-input')[0];
            let to = element.querySelectorAll('.to-input')[0];
            let inputs = [from, to];
            let min = parseInt(from.getAttribute('data-value'));
            let max = parseInt(to.getAttribute('data-value'));
            let range = [parseInt(from.getAttribute('data-init')), parseInt(to.getAttribute('data-init'))];
            let step = parseInt(element.getAttribute('data-step'));
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

            from.addEventListener('change', function () {
                stepsSlider.noUiSlider.set([this.value, null]);
            });

            to.addEventListener('change', function () {
                stepsSlider.noUiSlider.set([null, this.value]);
            });

        });
    }

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
});

(function ($) {
    $(document).ready(function(){
        //$('[data-toggle="tooltip"]').tooltip();
        //$('[data-toggle="popover"]').popover();
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