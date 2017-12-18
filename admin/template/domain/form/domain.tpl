<div class="row">
    <form id="edit_domain" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$domain.id_domain}" method="post" class="validate_form edit_form col-ph-12 col-md-6">
        <div class="row">
            <div class="col-xs-8">
                <div class="form-group">
                    <label for="url_domain">{#url_domain#|ucfirst}</label>
                    <input type="text" class="form-control required" name="url_domain" id="url_domain" placeholder="{#ph_url_domain#|ucfirst}" {if $domain.url_domain != null} value="{$domain.url_domain}"{/if} required>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label>{#default_domain#|ucfirst}&nbsp;*</label>
                    <div class="radio">
                        <label for="default_1">
                            <input type="radio" name="default_domain" id="default_1" value="1" {if $domain.default_domain == 1} checked{/if} required>
                            {#bin_1#}
                        </label>
                        <label for="default_0">
                            <input type="radio" name="default_domain" id="default_0" value="0" {if $domain.default_domain == 0} checked{/if} required>
                            {#bin_0#}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="data_type" name="data_type" value="domain">
        <input type="hidden" id="id_domain" name="id" value="{$domain.id_domain}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>