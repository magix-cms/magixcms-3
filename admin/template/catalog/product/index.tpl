{extends file="layout.tpl"}
{block name='head:title'}{#catalog_product#|ucfirst}{/block}
{block name='body:id'}catalog-product{/block}

{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="pull-right">
        <p class="text-right">
            {#nbr_product#|ucfirst}: {$pages|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_product#}" class="btn btn-link">
                <span class="fa fa-plus"></span> {#add_product#|ucfirst}
            </a>
        </p>
    </div>
    {/if}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des produits">{#catalog_product#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_product#|ucfirst}</h2>
            </header>
            <div class="panel-body">
                <div class="mc-message-container clearfix">
                    <div class="mc-message mc-message-pages">{if isset($message)}{$message}{/if}</div>
                </div>
                {*{if isset($scheme)}{$scheme|var_dump}{/if}*}
                {*{if $smarty.get.search}{$sortable = false}{else}{$sortable = true}{/if}*}
                {include file="section/form/table-form-3.tpl" data=$pages idcolumn='id_product' activation=false sortable=false controller="product" change_offset=true}
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='product' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
    {include file="modal/error.tpl"}
    {else}
    {include file="section/brick/viewperms.tpl"}
{/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f={baseadmin}/template/js/table-form.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function() {
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            if (typeof tableForm == "undefined") {
                console.log("tableForm is not defined");
            } else {
                tableForm.run(controller);
            }
        });
    </script>
{/block}