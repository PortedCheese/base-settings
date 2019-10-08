<div class="form-group">
    <label for="userAdminPager">Пагинация на странице пользователей</label>
    <input type="number"
           min="1"
           required
           id="userAdminPager"
           name="data-userAdminPager"
           value="{{ old("data-userAdminPager", $config->data["userAdminPager"]) }}"
           class="form-control @error("data-userAdminPager") is-invalid @enderror">
    @error("data-userAdminPager")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="form-group">
    <label for="recaptchaSiteKey">Recaptcha site key</label>
    <input type="text"
           required
           id="recaptchaSiteKey"
           name="data-recaptchaSiteKey"
           value="{{ old("data-recaptchaSiteKey", $config->data["recaptchaSiteKey"]) }}"
           class="form-control @error("data-recaptchaSiteKey") is-invalid @enderror">
    @error("data-recaptchaSiteKey")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="form-group">
    <label for="recaptchaSiteKey">Recaptcha secret key</label>
    <input type="text"
           required
           id="recaptchaSecretKey"
           name="data-recaptchaSecretKey"
           value="{{ old("data-recaptchaSecretKey", $config->data["recaptchaSecretKey"]) }}"
           class="form-control @error("data-recaptchaSecretKey") is-invalid @enderror">
    @error("data-recaptchaSecretKey")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>
