<h3>Ajouter une langue au domaine</h3>
<div class="row">
    <div class="col-ph-12 col-md-4">
        <div class="form-group">
            <label>{#default_lang#|ucfirst}&nbsp;*</label>
            <div class="radio">
                <label for="default_1">
                    <input type="radio" name="default_lang" id="default_1" value="1" required>
                    {#bin_1#}
                </label>
                <label for="default_0">
                    <input type="radio" name="default_lang" id="default_0" value="0" checked required>
                    {#bin_0#}
                </label>
            </div>
        </div>
    </div>
    <div class="col-ph-12 col-md-8">
        <div class="form-group">
            <label for="id_lang">{#language#|ucfirst}</label>
            <select name="id_lang" id="id_lang" class="form-control required">
                <option value="">{#ph_language#|ucfirst}</option>
                {foreach $language as $key => $val}
                    <option value="{$val.id_lang}">{$val.name_lang|ucfirst}</option>
                {/foreach}
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div id="submit" class="col-ph-12 col-md-6">
        <input type="hidden" id="id_domain" name="id" value="{$id}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
    </div>
</div>