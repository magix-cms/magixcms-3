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
            <h2 class="panel-heading h5">{#translate#|ucfirst} du théme {$smarty.get.skin}</h2>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            {include file="translate/form/edit.tpl" controller="translate"}
        </div>
    </section>
</div>
{else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}