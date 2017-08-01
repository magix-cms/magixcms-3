{extends file="layout.tpl"}
{block name='head:title'}{#edit_category#|ucfirst}{/block}
{block name='body:id'}catalog-category{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des catégories">{#catalog_category#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-xs-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_category#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#}</a></li>
                    <li role="presentation"><a href="#image" aria-controls="image" role="tab" data-toggle="tab">{#image#}</a></li>
                    <li role="presentation"><a href="#child" aria-controls="child" role="tab" data-toggle="tab">{#child_page#}</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general">
                        {include file="catalog/category/form/edit.tpl" controller="category"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="image">
                        {include file="catalog/category/form/img.tpl" controller="category"}
                        {*<pre>{$page|print_r}</pre>*}
                        <div class="row">
                            <div class="block-img">
                            {if $page.imgSrc != null}
                            {include file="catalog/category/brick/img.tpl"}
                            {/if}
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane tab-table" id="child">
                        <p class="text-right">
                            {#nbr_category#|ucfirst}: {$pagesChild|count} <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add&parent_id={$smarty.get.edit}" title="{#add_category#}" class="btn btn-link">
                                <span class="fa fa-plus"></span> {#add_category#|ucfirst}
                            </a>
                        </p>
                        {if $smarty.get.search}{$sortable = false}{else}{$sortable = true}{/if}
                        {include file="section/form/table-form-2.tpl" ajax_form=true idcolumn='id_cat' data=$pagesChild activation=true sortable=$sortable controller="category"}
                    </div>
                </div>
                {*<pre>{$page|print_r}</pre>*}
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/pages.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            if (typeof pages == "undefined")
            {
                console.log("pages is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                pages.run(controller);
            }
        });
    </script>
{/block}