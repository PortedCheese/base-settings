@push('js-lib')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

<div class="g-recaptcha" data-sitekey="{{ env("RECAPTCHA_SITEKEY") }}"></div>