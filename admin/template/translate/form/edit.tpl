{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_translate" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=translate&amp;skin={$smarty.get.skin}" method="post" class="validate_form edit_form col-xs-6">
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        {foreach $translate.content[$id] as $key => $value nocache}
                            <div class="form-group">
                                <label for="config[{$iso}][{$key}]">{$key}</label>
                                <input type="text" class="form-control" name="config[{$iso}][{$key}]" value="{$value|escape:'html'}" />
                            </div>
                        {/foreach}
                    </fieldset>
                    {/foreach}
                </div>
            </div>
        </div>
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>
