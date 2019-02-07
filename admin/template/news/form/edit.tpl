{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_news" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}" method="post" class="validate_form edit_form_extend col-ph-12 col-md-6">
        <div class="tab-content">
            {foreach $langs as $id => $iso}
                <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][name_news]">{#title#|ucfirst} :</label>
                                <input type="text" class="form-control" id="content[{$id}][name_news]" name="content[{$id}][name_news]" value="{$page.content[{$id}].name_news}" size="50" />
                            </div>
                        </div>
                        <div class="col-ph-12 col-sm-4">
                            <div class="form-group">
                                <label for="content[{$id}][published_news]">{#status#|ucfirst}</label>
                                <input id="content[{$id}][published_news]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_news]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published_news} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][url_news]">{#url_rewriting#|ucfirst}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="content[{$id}][url_news]" name="content[{$id}][url_news]" readonly="readonly" size="30" value="{$page.content[{$id}].url_news}" />
                                    <span class="input-group-addon">
                                        <a class="unlocked" href="#">
                                            <span class="fa fa-lock"></span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-ph-12 col-sm-4">
                            <div class="form-group">
                                <label for="content[{$id}][date_publish]">Date de publication</label>
                                <div class="input-group date date-input-picker">
                                    <input type="text" class="form-control" id="content[{$id}][date_publish]" name="content[{$id}][date_publish]" value="{$page.content[{$id}].date_publish}" size="50" />
                                    <span class="input-group-addon">
                                        <span class="far fa-calendar-plus"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="public-url[{$id}]">URL</label>
                                <input type="text" class="form-control public-url" data-lang="{$id}" id="public_url[{$id}]" readonly="readonly" size="50" value="{$page.content[{$id}].public_url}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content[{$id}][resume_news]">{#resume#|ucfirst} :</label>
                        <textarea name="content[{$id}][resume_news]" id="content[{$id}][resume_news]" class="form-control">{$page.content[{$id}].resume_news}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="content[{$id}][content_news]">{#content#|ucfirst} :</label>
                        <textarea name="content[{$id}][content_news]" id="content[{$id}][content_news]" class="form-control mceEditor">{call name=cleantextarea field=$page.content[{$id}].content_news}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-ph-12 col-sm-12">
                        <div class="form-group">
                            <label for="content[{$id}][tag_news]">{#news_tag#|ucfirst}Tags :</label>
                            <input type="text" class="tags-input" value="{$page.content[{$id}].tags_news}" data-lang="{$id}" {*data-role="tagsinput"*} name="content[{$id}][tag_news]" id="tag-news-{$id}"/>
                            <input type="hidden" id="auto-tag-{$id}" disabled="disabled" value="{$page.content[{$id}].tags}" />
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
                                    <label for="content[{$id}][seo_title_news]">{#title#|ucfirst} :</label>
                                    <textarea class="form-control" id="content[{$id}][seo_title_news]" name="content[{$id}][seo_title_news]" cols="70" rows="3">{$page.content[{$id}].seo_title_news}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-ph-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][seo_desc_news]">Description :</label>
                                    <textarea class="form-control" id="content[{$id}][seo_desc_news]" name="content[{$id}][seo_desc_news]" cols="70" rows="3">{$page.content[{$id}].seo_desc_news}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            {/foreach}
        </div>
        <input type="hidden" id="id_news" name="id" value="{$page.id_news}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>