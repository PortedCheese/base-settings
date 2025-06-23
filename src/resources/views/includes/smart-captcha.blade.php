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

    @error("smart-token")
        <input type="hidden" class="form-control is-invalid">
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
@endguest