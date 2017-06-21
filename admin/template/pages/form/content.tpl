{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_pages" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_pages}" method="post" class="validate_form edit_form col-xs-12 col-md-6">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][name_pages]">{#title#|ucfirst} *:</label>
                                        <input type="text" class="form-control" id="content[{$id}][name_pages]" name="content[{$id}][name_pages]" value="{$page.content[{$id}].name_pages}" size="50" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="content[{$id}][published_pages]">Statut</label>
                                        <input id="content[{$id}][published_pages]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_pages]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published_pages} checked{/if}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][url_pages]">{#url_rewriting#|ucfirst}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="content[{$id}][url_pages]" name="content[{$id}][url_pages]" readonly="readonly" size="30" value="{$page.content[{$id}].url_pages}" />
                                            <span class="input-group-addon">
                                                            <a class="unlocked" href="#">
                                                                <span class="fa fa-lock"></span>
                                                            </a>
                                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content[{$id}][content_pages]">{#content#|ucfirst} :</label>
                                        <textarea name="content[{$id}][content_pages]" id="content[{$id}][content_pages]" class="form-control mceEditor">{cleanTextarea field=$page.content[{$id}].content_pages}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn collapsed btn-collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#metas-{$id}" aria-expanded="true" aria-controls="metas-{$id}">
                                    <span class="fa"></span> {#display_metas#|ucfirst}
                                </button>
                            </div>

                            <div id="metas-{$id}" class="collapse" role="tabpanel" aria-labelledby="heading{$id}">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8">
                                        <div class="form-group">
                                            <label for="content[{$id}][seo_name_pages]">{#title#|ucfirst} :</label>
                                            <textarea class="form-control" id="content[{$id}][seo_name_pages]" name="content[{$id}][seo_name_pages]" cols="70" rows="3">{$page.content[{$id}].seo_name_pagess}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8">
                                        <div class="form-group">
                                            <label for="content[{$id}][seo_desc_page]">Description :</label>
                                            <textarea class="form-control" id="content[{$id}][seo_desc_page]" name="content[{$id}][seo_desc_page]" cols="70" rows="3">{$page.content[{$id}].seo_desc_pages}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    {/foreach}
                </div>
            </div>
        </div>
        <input type="hidden" id="id_pages" name="id" value="{$page.id_pages}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>