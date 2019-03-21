<div class="row">
    <div class="col-ph-12">
        <div id="progress-thumbnail" class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar-state">
                    <span class="state">Connexion au serveur ...</span>
                </div>
            </div>
            <span class="state">Connexion au serveur ...</span>
        </div>
    </div>
    <form id="new_thumbnail" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="form-gen col-ph-12 col-sm-6 col-md-4">
        <div class="form-group">
            <label for="module_name">{#config_module_available#}</label>
            <select name="module_name" id="module_name" class="form-control has-optional-fields required" required>
                <option value="">{#ph_config#|ucfirst}</option>
                {foreach $setConfig as $key => $val}
                    {if $val.attr_name != 'about'}
                    <option value="{$val.attr_name}" class="optional-field" data-target="{if $val.attr_name eq 'catalog'}#subcat{/if}">
                        {if $val.attr_name eq 'pages' OR $val.attr_name eq 'news' OR $val.attr_name eq 'catalog'}{#$val.attr_name#|ucfirst}{else}{$val.attr_name}{/if}
                    </option>
                    {/if}
                {/foreach}
            </select>
        </div>
        <div id="subcat" class="additional-fields collapse">
            <div class="form-group">
                <label for="attr_name">{#config_attribute_available#}</label>
                <select name="attr_name" id="attr_name" class="form-control has-optional-fields">
                    <option value="" class="default" selected>{#ph_attribute#|ucfirst}</option>
                    <option value="category">{#categories#|ucfirst}</option>
                    <option value="product">{#products#|ucfirst}</option>
                </select>
            </div>
        </div>
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>