{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_translate" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=translate&amp;skin={$smarty.get.skin}" method="post" class="validate_form edit_form col-xs-6">
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        <ul class="list-group">
                        {foreach $translate.content[$id] as $key => $value nocache}
                            <li class="panel list-group-item">
                            {if is_array($value)}
                                <header>
                                    <span class="lead">{$key}</span>
                                    <div class="actions">
                                        <a href="#collapse{$value@index}" data-toggle="collapse" class="btn btn-link collapsed">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                    </div>
                                </header>
                                <div class="collapse" id="collapse{$value@index}">
                                    <div class="subform">
                                    {foreach $value as $k => $v nocache}
                                        <div class="form-group">
                                            <label for="config[{$iso}][{$key}][{$k}]">{$k}</label>
                                            <input type="text" class="form-control" name="config[{$iso}][{$key}][{$k}]" value="{$v|escape:'html'}" />
                                        </div>
                                    {/foreach}
                                    </div>
                                </div>
                            {else}
                                <div class="form-group">
                                    <label for="config[{$iso}][{$key}]">{$key}</label>
                                    <input type="text" class="form-control" name="config[{$iso}][{$key}]" value="{$value|escape:'html'}" />
                                </div>
                            {/if}
                            </li>
                        {/foreach}
                        </ul>
                    </fieldset>
                    {/foreach}
                </div>
            </div>
        </div>
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>
