{extends file="layout.tpl"}
{block name='head:title'}{#edit_img#|ucfirst}{/block}
{block name='body:id'}catalog-product{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des produits">{#catalog_product#|ucfirst}</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12 col-md-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header panel-nav">
                <h2 class="panel-heading h5">{#edit_img#|ucfirst}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <form id="edit_product_img" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$img.id_product}&editimg={$img.id_img}" method="post" class="validate_form add_form col-ph-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <label for="name_img_{$id}">{#name_img#|ucfirst} :</label>
                            <input type="text" class="form-control" placeholder="{#ph_name_img#}" id="name_img_{$id}" name="name_img" value="{$img.name_img_we}" />
                        </div>
                        <img src="/upload/catalog/p/{$img.id_product}/m_{$img.name_img}" class="img-responsive" />
                        {include file="language/brick/dropdown-lang.tpl"}
                        <div class="tab-content">
                            {foreach $langs as $id => $iso}
                                <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                                    <legend>Texte</legend>
                                    <div class="row">
                                        <div class="col-ph-12 col-md-6">
                                            <div class="form-group">
                                                <label for="imgData[{$id}][alt_img]">{#alt_img#|ucfirst}</label>
                                                <input type="text" class="form-control" id="imgData[{$id}][alt_img]" name="imgData[{$id}][alt_img]" value="{$img.content[{$id}].alt_img}" placeholder="{#ph_alt_img#|ucfirst}" />
                                            </div>
                                        </div>
                                        <div class="col-ph-12 col-md-6">
                                            <div class="form-group">
                                                <label for="imgData[{$id}][title_img]">{#title_img#|ucfirst}</label>
                                                <input type="text" class="form-control" id="imgData[{$id}][title_img]" name="imgData[{$id}][title_img]" value="{$img.content[{$id}].title_img}" placeholder="{#ph_title_img#|ucfirst}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="imgData[{$id}][caption_img]">{#caption_img#|ucfirst}</label>
                                        <textarea class="form-control" id="imgData[{$id}][caption_img]" name="imgData[{$id}][caption_img]" placeholder="{#ph_caption_img#|ucfirst}">{$img.content[{$id}].caption_img}</textarea>
                                    </div>
                                </fieldset>
                            {/foreach}
                        </div>
                        <fieldset>
                            <legend>{#save#|ucfirst}</legend>
                            <input type="hidden" id="id_img" name="id_img" value="{$img.id_img}">
                            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                        </fieldset>
                    </form>
                </div>
                {*<pre>{$img|print_r}</pre>*}
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
                var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&action=edit&edit={$img.id_product}";
                globalForm.run(controller);
            }
        });
    </script>
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/product.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof product == "undefined")
            {
                console.log("product is not defined");
            }else{
                product.run(controller);
            }
        });
    </script>
{/block}