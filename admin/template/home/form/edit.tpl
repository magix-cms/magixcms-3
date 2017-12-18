{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_home" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-8">
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <div class="row">
                                <div class="col-ph-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][title_page]">{#title#|ucfirst} *:</label>
                                        <input type="text" class="form-control" id="content[{$id}][title_page]" name="content[{$id}][title_page]" value="{$page.content[{$id}].title_page}" size="50" />
                                    </div>
                                </div>
                                <div class="col-ph-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="content[{$id}][published]">Statut</label>
                                        <input id="content[{$id}][published]" data-toggle="toggle" type="checkbox" name="content[{$id}][published]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published} checked{/if}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-ph-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content[{$id}][content_page]">{#content#|ucfirst} :</label>
                                        <textarea name="content[{$id}][content_page]" id="content[{$id}][content_page]" class="form-control mceEditor">{call name=cleantextarea field=$page.content[{$id}].content_page}</textarea>
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
                                    <div class="col-ph-12 col-sm-8">
                                        <div class="form-group">
                                            <label for="content[{$id}][seo_title_page]">{#title#|ucfirst} :</label>
                                            <textarea class="form-control" id="content[{$id}][seo_title_page]" name="content[{$id}][seo_title_page]" cols="70" rows="3">{$page.content[{$id}].seo_title_page}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-ph-12 col-sm-8">
                                        <div class="form-group">
                                            <label for="content[{$id}][seo_desc_page]">Description :</label>
                                            <textarea class="form-control" id="content[{$id}][seo_desc_page]" name="content[{$id}][seo_desc_page]" cols="70" rows="3">{$page.content[{$id}].seo_desc_page}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    {/foreach}
                </div>
            </div>
        </div>
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>