{extends file="layout.tpl"}
{block name='head:title'}{#edit_product#|ucfirst}{/block}
{block name='body:id'}catalog-product{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des produits">{#catalog_product#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_product#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#}</a></li>
                    <li role="presentation"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">{#images#|ucfirst}</a></li>
                    <li role="presentation"><a href="#cat" aria-controls="cat" role="tab" data-toggle="tab">Catégories</a></li>
                    <li role="presentation"><a href="#similar" aria-controls="similar" role="tab" data-toggle="tab">Similaires</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general">
                        {include file="catalog/product/form/edit.tpl" controller="product"}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="images">
                        {*<pre>{$images|print_r}</pre>*}
                        {include file="catalog/product/form/img.tpl" controller="product"}
                        <div id="gallery-product" class="block-img">
                            {if $images != null}
                                {include file="catalog/product/brick/img.tpl"}
                            {/if}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="cat">
                        <form action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_product}&tabs=cat" method="post" class="validate_form">
                            <div class="row">
                                <div class="col-ph-12 col-sm-6 col-md-4">
                                    <div class="tree-header">
                                        <a href="#" class="tree-actions" data-action="toggle-down"><span class="fa fa-angle-down"></span> Déployer</a>
                                        <a href="#" class="tree-actions" data-action="toggle-up"><span class="fa fa-angle-up"></span> Réduire</a>
                                        <span class="pull-right">Catégorie par défaut</span>
                                    </div>
                                    <div class="catlisting">
                                        {include file="catalog/product/loop/cat.tpl" cats=$catTree}
                                    </div>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="hidden" name="product_cat" value="save"/>
                                <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="similar">
                        {*<p class="text-right">
                            {#nbr_product_rel#|ucfirst}: {$productRel|count} <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add&product_id={$page.id_product}" title="{#add_product_rel#}" class="btn btn-link">
                                <span class="fa fa-plus"></span> {#add_product_rel#|ucfirst}
                            </a>
                        </p>
                        {include file="section/form/table-form-2.tpl" data=$productRel idcolumn='id_rel' activation=false sortable=$sortable controller="product" subcontroller="similar" edit=false search=false}
                        <hr>*}
                        {include file="section/form/list-form.tpl" controller="product" sub="similar" dir_controller="catalog/product" data=$productRel id=$page.id_product class_form="col-ph-12 col-lg-5" class_table="col-ph-12 col-lg-7"}
                    </div>
                </div>
                {*<pre>{$page|print_r}</pre>*}
            </div>
        </section>
    </div>
        {include file="modal/delete.tpl" data_type='product' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_img_message#}}
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        libjs/vendor/progressBar.min.js,
        {baseadmin}/template/js/table-form.min.js,
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/bootstrap-select.min.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/product.min.js
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
            if (typeof product == "undefined")
            {
                console.log("product is not defined");
            }else{
                var edit = "{$smarty.get.edit}";
                product.run(globalForm,tableForm,edit);
                product.runAdd();
            }
        });
    </script>
{/block}