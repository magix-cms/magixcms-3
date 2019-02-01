{extends file="layout.tpl"}
{block name='head:title'}{#edit_category#|ucfirst}{/block}
{block name='body:id'}catalog-category{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des catégories">{#catalog_category#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_category#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#|ucfirst}</a></li>
                    <li role="presentation"><a href="#image" aria-controls="image" role="tab" data-toggle="tab">{#image#|ucfirst}</a></li>
                    <li role="presentation"><a href="#child" aria-controls="child" role="tab" data-toggle="tab">{#child_page#|ucfirst}</a></li>
                    <li role="presentation"><a href="#products" aria-controls="products" role="tab" data-toggle="tab">{#products#|ucfirst}</a></li>
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
                        {*<div class="block-img">
                            {if $page.imgSrc != null}
                                {include file="catalog/category/brick/img.tpl"}
                            {/if}
                        </div>*}
                    </div>
                    <div role="tabpanel" class="tab-pane tab-table" id="child">
                        <p class="text-right">
                            {#nbr_category#|ucfirst}: {$pagesChild|count} <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add&parent_id={$smarty.get.edit}" title="{#add_category#}" class="btn btn-link">
                                <span class="fa fa-plus"></span> {#add_category#|ucfirst}
                            </a>
                        </p>
                        {if $smarty.get.search}{$sortable = false}{else}{$sortable = true}{/if}
                        {include file="section/form/table-form-3.tpl" ajax_form=true idcolumn='id_cat' data=$pagesChild activation=true sortable=$sortable controller="category"}
                    </div>
                    <div role="tabpanel" class="tab-pane tab-table" id="products">
                        {include file="section/form/table-form-3.tpl" data=$catalog idcolumn='id_catalog' activation=false sortable=$sortable controller="category" subcontroller="product" scheme=$schemeCatalog search=false editColumn='id_product' editController='product'}
                        {*<pre>{$catalog|print_r}</pre>*}
                    </div>
                </div>

            </div>
        </section>
    </div>
        {include file="modal/delete.tpl" data_type='category' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
        {include file="modal/error.tpl"}
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/src/bootstrap-select.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/table-form.min.js,
        {baseadmin}/template/js/img-drop.min.js,
        {baseadmin}/template/js/category.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller);
            }
            if (typeof category == "undefined")
            {
                console.log("category is not defined");
            }else{
                category.runAdd(controller);
            }
        });
    </script>
{/block}