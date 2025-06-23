@guest
    @push('js-lib')
        <script src="https://smartcaptcha.yandexcloud.net/captcha.js" async defer></script>
    @endpush

    <div
            style="height: 100px"
            id="captcha-container"
            class="smart-captcha"
            data-sitekey="{{ siteconf()->get("base-settings", "recaptchaSiteKey") }}"
    ></div>

    @if (! empty($errors) && $errors->has('smart-token'))
        <input type="hidden" class="form-control is-invalid">
        <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('smart-token') }}</strong>
    </span>
    @endif
@endguest