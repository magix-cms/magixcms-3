{extends file="layout.tpl"}
{block name='head:title'}{#translate_of_plugin#|ucfirst} {$smarty.get.controller}{/block}
{block name='body:id'}translate{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher le plugin {$smarty.get.controller}">{#translate_of_plugin#|ucfirst} {$smarty.get.controller}</a></h1>
{/block}
{block name='article:content'}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-8">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#translate_plugin#}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                {include file="language/brick/dropdown-lang.tpl"}
                <div class="row">
                    <form id="edit_translate" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=translate" method="post" class="validate_form edit_form col-xs-12">
                        <div class="row">
                            <div class="col-ph-12">
                                <div class="tab-content">
                                    {foreach $langs as $id => $iso}
                                        <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                                            <ul class="list-group">
                                                {nocache}
                                                {foreach $translate.content[$id] as $key => $value}
                                                    <li class="panel list-group-item">
                                                        {if is_array($value)}
                                                            <header>
                                                                <span class="lead">{$key}</span>
                                                                <div class="actions">
                                                                    <a href="#collapse{$iso}{$value@index}" data-toggle="collapse" class="btn btn-link collapsed">
                                                                        <i class="material-icons">more_vert</i>
                                                                    </a>
                                                                </div>
                                                            </header>
                                                            <div class="collapse" id="collapse{$iso}{$value@index}">
                                                                <div class="subform">
                                                                    {foreach $value as $k => $v}
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
                                                {/nocache}
                                            </ul>
                                        </fieldset>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
{/block}