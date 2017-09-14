{extends file="layout.tpl"}
{block name='head:title'}{#add_product_rel#|ucfirst}{/block}
{block name='body:id'}catalog-product{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des produits">{#catalog_product#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{#add_product_rel#|ucfirst}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="catalog/product/form/similar.tpl" controller="product"}
                </div>
            </section>
        </div>
    {/if}
{/block}
{block name="foot"}
    {script src="/{baseadmin}/min/?g=publicjs,globalize,jimagine" type="javascript"}
    {script src="/{baseadmin}/min/?f={baseadmin}/template/js/global.min.js,libjs/vendor/jquery.formatter.min.js" type="javascript"}
    {script src="/{baseadmin}/min/?f={baseadmin}/template/js/form.min.js" type="javascript"}
    <script type="text/javascript">
        $.jmRequest.notifier = {
            cssClass : '.mc-message'
        };
        var editor_version = "{$smarty.const.VERSION_EDITOR}";
        var baseadmin = "{baseadmin}";
        var iso = "{iso}";

        $(function(){
            if (typeof globalForm == "undefined")
            {
                console.log("globalForm is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&action=edit&edit={$smarty.get.product_id}";
                globalForm.run(controller);
            }
        });
    </script>

    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/bootstrap-select.min.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/product.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof product == "undefined")
            {
                console.log("product is not defined");
            }else{
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
                product.runAdd(controller);
            }
        });
    </script>
{/block}