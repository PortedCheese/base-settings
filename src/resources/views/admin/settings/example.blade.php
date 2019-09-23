<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox"
               class="custom-control-input"
               id="check"
               {{ (! count($errors->all()) && $config->data['check']) || old("check") ? "checked" : "" }}
               name="data-check">
        <label class="custom-control-label" for="check">Check</label>
    </div>
</div>
