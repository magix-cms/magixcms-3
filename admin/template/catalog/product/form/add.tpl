<form id="add_product_content" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
    {include file="language/brick/dropdown-lang.tpl"}
    <div class="row">
        <div class="col-ph-12 col-md-6">
            <div class="tab-content">
                {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        <div class="row">
                            <div class="col-ph-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][name_p]">{#name_p#|ucfirst} *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="content[{$id}][name_p]" name="content[{$id}][name_p]" value="" maxlength="65" />
                                        <span class="input-group-addon">
                                            <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{#name_p_info#}">
                                                <span class="fa fa-question-circle"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-ph-12 col-sm-4">
                                <div class="form-group">
                                    <label for="content[{$id}][published_p]">Statut</label>
                                    <input id="content[{$id}][published_p]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_p]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger">
                                </div>
                            </div>
                            <div class="col-ph-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][longname_p]">{#longname_p#|ucfirst}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="content[{$id}][longname_p]" name="content[{$id}][longname_p]" value="" maxlength="125" />
                                        <span class="input-group-addon">
                                            <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{#longname_p_info#}">
                                                <span class="fa fa-question-circle"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-ph-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][url_p]">{#url_rewriting#|ucfirst}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="content[{$id}][url_p]" name="content[{$id}][url_p]" readonly="readonly" size="30" value="" />
                                        <span class="input-group-addon">
                                            <a class="unlocked" href="#">
                                                <span class="fa fa-lock"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content[{$id}][resume_p]">{#resume#|ucfirst} :</label>
                            <textarea name="content[{$id}][resume_p]" id="content[{$id}][resume_p]" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="content[{$id}][content_p]">{#content#|ucfirst} :</label>
                            <textarea name="content[{$id}][content_p]" id="content[{$id}][content_p]" class="form-control mceEditor"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn collapsed btn-collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#metas-{$id}" aria-expanded="true" aria-controls="metas-{$id}">
                                <span class="fa"></span> {#display_metas#|ucfirst}
                            </button>
                        </div>
                        <div id="metas-{$id}" class="collapse" role="tabpanel" aria-labelledby="heading{$id}">
                            <div class="row">
                                <div class="col-ph-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][seo_title_p]">{#title#|ucfirst} :</label>
                                        <textarea class="form-control" id="content[{$id}][seo_title_p]" name="content[{$id}][seo_title_p]" cols="70" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-ph-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][seo_desc_p]">Description :</label>
                                        <textarea class="form-control" id="content[{$id}][seo_desc_p]" name="content[{$id}][seo_desc_p]" cols="70" rows="3"></textarea>
                                    </div>
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
                <input type="text" class="form-control" id="reference_p" name="productData[reference]" value="" placeholder="{#ph_reference#|ucfirst}">
            </div>
            <h4>{#price#|ucfirst}</h4>
            <div class="row">
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="price_p">{#ht#}</label>
                        <input type="text" class="form-control" id="price_p" name="productData[price]" value="" placeholder="{#ph_price_vat#|ucfirst}">
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
    <button class="btn btn-main-theme" type="submit" name="action" value="add">{#save#|ucfirst}</button>
</form>