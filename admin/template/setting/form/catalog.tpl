{assign var="collectionformPrice" value=[
"tinc"=>"taxe incluse",
"texc"=>"taxe non comprise"
]}
<div class="row">
    <form id="edit_setting_catalog" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-sm-8 col-md-6">
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
                {foreach $collectionformPrice as $key => $val}
                    <option value="{$key}" {if $settings.price_display == $key} selected{/if}>{$val|ucfirst}</option>
                {/foreach}
            </select>
        </div>
        <input type="hidden" id="type" name="type" value="catalog">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>