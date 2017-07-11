<form id="edit_company" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-xs-12 col-md-10">
    <div class="row">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="company_name">{#company_name#|ucfirst}</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" {if $companyData.name}value="{$companyData.name}" {/if}placeholder="{#company_name_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="company_type">{#company_type#|ucfirst}</label>
                    <select name="company_type" id="company_type" class="form-control">
                        <option value selected disabled>-- {#company_type_ph#|ucfirst} --</option>
                        {foreach $schemaTypes as $key => $val}
                            <option value="{$key}"{if $companyData.type == $key} selected{/if}>{$val.label}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="company_tva">{#company_tva#|ucfirst}</label>
                    <input type="text" class="form-control" id="company_tva" name="company_tva" {if $companyData.tva}value="{$companyData.tva}" {/if}placeholder="{#company_tva_ph#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <label for="company_eshop">{#company_eshop#|ucfirst}</label>
                    <input id="company_eshop" data-toggle="toggle" type="checkbox" name="company_eshop" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $companyData.eshop} checked{/if}>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-4">
            <input type="hidden" id="data_type" name="data_type" value="company">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </div>
</form>