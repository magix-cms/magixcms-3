{extends file="layout.tpl"}
{block name='head:title'}{#files_configuration#|ucfirst}{/block}
{block name='body:id'}files{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des tailles d'image">{#files#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
{if {employee_access type="edit" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-ph-12 col-md-6">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header">
            <h2 class="panel-heading h5">{#edit_imagesize#|ucfirst}</h2>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            {include file="files/form/edit.tpl"}
        </div>
    </section>
</div>
{/if}
{/block}