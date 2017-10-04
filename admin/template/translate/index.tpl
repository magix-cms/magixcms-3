{extends file="layout.tpl"}
{block name='head:title'}{#translate#|ucfirst}{/block}
{block name='body:id'}translate{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des thèmes">{#translate#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="edit" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header">
            <h2 class="panel-heading h5">{#translate#|ucfirst}</h2>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>

            <form id="edit_translate" action="{$smarty.server.SCRIPT_NAME}" method="get" class="col-xs-6">
                <input type="hidden" name="controller" value="{$smarty.get.controller}" />
                <input type="hidden" name="action" value="translate" />
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <label for="skin">{#translate_theme#|ucfirst}</label>
                            <select name="skin" id="skin" class="form-control required" required>
                                <option value="">{#ph_theme#|ucfirst}</option>
                                {foreach $getSkin as $key}
                                    <option value="{$key.name}">{$key.name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-main-theme pull-right" type="submit">{#save#|ucfirst}</button>
            </form>
        </div>
    </section>
</div>
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}