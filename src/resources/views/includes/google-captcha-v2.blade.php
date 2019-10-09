@guest
    @push('js-lib')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush

    <div class="g-recaptcha" data-sitekey="{{ siteconf()->get("base-settings", "recaptchaSiteKey") }}"></div>

    @error("g-recaptcha-response")
        <input type="hidden" class="form-control is-invalid">
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
@endguest