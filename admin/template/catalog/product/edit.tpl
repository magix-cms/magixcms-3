{extends file="layout.tpl"}
{block name='head:title'}{#edit_product#|ucfirst}{/block}
{block name='body:id'}catalog-product{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des produits">{#catalog_product#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-xs-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_product#|ucfirst}</h2>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#}</a></li>
                    <li role="presentation" class="active"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">{#images#}</a></li>
                    <li role="presentation"><a href="#cat" aria-controls="cat" role="tab" data-toggle="tab">categories</a></li>
                </ul>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="general">
                        {include file="catalog/product/form/edit.tpl" controller="product"}
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="images">
                        {*<pre>{$images|print_r}</pre>*}
                        {include file="catalog/product/form/img.tpl" controller="product"}
                        <div id="gallery-product" class="block-img">
                            {if $images != null}
                                {include file="catalog/product/brick/img.tpl"}
                            {/if}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="cat">
                        <ul class="list-unstyled">
                        {foreach $catRoot as $cat}
                            <li>
                            {$cat.name_cat}
                            {if $cat.parent_id != NULL}
                            <a class="tree-toggle" type="button" data-toggle="collapse" href="#csc-{$cat.id_cat}" aria-expanded="false" aria-controls="csc-{$cat.id_cat}"><span class="fa fa-plus-square"></span></a>
                            <div id="csc-{$cat.id_cat}" class="cat-tree collapse"></div>
                            {/if}
                            </li>
                            {/foreach}
                        </ul>
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
        {baseadmin}/template/js/product.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run();
            }
            if (typeof product == "undefined")
            {
                console.log("product is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                product.run(controller);
            }
        });
    </script>
{/block}