{include file="language/brick/dropdown-lang.tpl"}
<form id="edit_product_content" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_product}" method="post" class="validate_form edit_form_extend">
    <div class="row">
        <div class="col-ph-12 col-md-6">
            <div class="tab-content">
                {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        <div class="row">
                            <div class="col-xs-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][name_p]">{#title#|ucfirst} *</label>
                                    <input type="text" class="form-control" id="content[{$id}][name_p]" name="content[{$id}][name_p]" value="{$page.content[{$id}].name_p}" size="50" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="content[{$id}][published_p]">Statut</label>
                                    <input id="content[{$id}][published_p]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_p]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published_p} checked{/if}>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][url_p]">{#url_rewriting#|ucfirst}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="content[{$id}][url_p]" name="content[{$id}][url_p]" readonly="readonly" size="30" value="{$page.content[{$id}].url_p}" />
                                        <span class="input-group-addon">
                                    <a class="unlocked" href="#">
                                        <span class="fa fa-lock"></span>
                                    </a>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {*<div class="row">
                            <div class="col-xs-12 col-sm-8">
                                <div class="form-group">
                                    <label for="public-url[{$id}]">URL</label>
                                    <input type="text" class="form-control public-url" data-lang="{$id}" id="public_url[{$id}]" readonly="readonly" size="50" value="{$page.content[{$id}].public_url}" />
                                </div>
                            </div>
                        </div>*}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label for="content[{$id}][content_p]">{#content#|ucfirst} :</label>
                                    <textarea name="content[{$id}][content_p]" id="content[{$id}][content_p]" class="form-control mceEditor">{call name=cleantextarea field=$page.content[{$id}].content_p}</textarea>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                {/foreach}
            </div>
        </div>
        <div class="col-ph-12 col-md-4">
            <div class="form-group">
                <label for="reference_p">{#reference#|ucfirst}</label>
                <input type="text" class="form-control" id="reference_p" name="productData[reference]" value="{$page.reference}" placeholder="{#ph_reference#|ucfirst}">
            </div>
            <h4>{#price#|ucfirst}</h4>
            <div class="row">
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="price_p">{#ht#}</label>
                        <input type="text" class="form-control" id="price_p" name="productData[price]" value="{$page.price_p}" placeholder="{#ph_price_vat#|ucfirst}">
                    </div>
                </div>
                {*<div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="price_ttc">{#ttc#}</label>
                        <input type="text" class="form-control" id="price_ttc" name="catalog[price_ttc]" value="" />
                    </div>
                </div>*}
            </div>
        </div>
    </div>
    <input type="hidden" id="id_product" name="id" value="{$page.id_product}">
    <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
</form>