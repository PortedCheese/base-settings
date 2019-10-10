<div class="form-group">
    <label for="userAdminPager">Пагинация на странице пользователей</label>
    <input type="number"
           min="1"
           required
           id="userAdminPager"
           name="data-userAdminPager"
           value="{{ old("data-userAdminPager", siteconf()->get($name, "userAdminPager", 10)) }}"
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
           value="{{ old("data-recaptchaSiteKey", siteconf()->get($name, "recaptchaSiteKey", "")) }}"
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
           value="{{ old("data-recaptchaSecretKey", siteconf()->get($name, "recaptchaSecretKey", "")) }}"
           class="form-control @error("data-recaptchaSecretKey") is-invalid @enderror">
    @error("data-recaptchaSecretKey")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="form-group">
    <label for="data-frontendDate">Дата frontend</label>
    <input type="date"
           id="data-frontendDate"
           name="data-frontendDate"
           value="{{ old("data-frontendDate", siteconf()->get($name, "frontendDate", "")) }}"
           class="form-control @error("data-frontendDate") is-invalid @enderror">
    @error("data-frontendDate")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

