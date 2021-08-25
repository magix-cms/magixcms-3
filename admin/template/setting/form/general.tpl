<form id="edit_setting_general" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <fieldset>
        <legend>{#catalog_setting#}</legend>
        <div class="row">
            <div class="col-ph-12 col-sm-6 col-md-5 col-lg-4">
                <div class="form-group">
                    <label for="vat_rate">{#vat_rate#|ucfirst}</label>
                    <div class="input-group">
                        <input type="text" id="vat_rate" name="setting[vat_rate]" class="form-control" value="{$settings.vat_rate}" />
                        <div class="input-group-addon"><span class="fas fa-percent"></span></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price_display">{#price_display#|ucfirst}</label>
                    <select name="setting[price_display]" id="price_display" class="form-control" required>
                        <option value="tinc" {if $settings.price_display === "tinc"} selected{/if}>{#tinc_setting#}</option>
                        <option value="texc" {if $settings.price_display === "texc"} selected{/if}>{#texc_setting#}</option>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-ph-12 col-sm-6 col-md-5 col-lg-4">
            <input type="hidden" id="type" name="type" value="catalog">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </div>
</form>