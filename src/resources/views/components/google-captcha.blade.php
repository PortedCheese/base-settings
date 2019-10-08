@guest
    @push('js-lib')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush

    <div class="g-recaptcha" data-sitekey="{{ siteconf()->get("base-settings", "recaptchaSiteKey") }}"></div>

    @if (! empty($errors) && $errors->has('g-recaptcha-response'))
        <input type="hidden" class="form-control is-invalid">
        <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
    </span>
    @endif
@endguest