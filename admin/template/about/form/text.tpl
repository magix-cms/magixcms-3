{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_company_text" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-10">
        <div class="tab-content">
            {foreach $langs as $id => $iso}
                <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][company_desc]">{#company_desc#|ucfirst} :</label>
                                <input type="text" class="form-control" id="content[{$id}][company_desc]" name="content[{$id}][company_desc]" value="{$contentData.{$id}.desc}" size="50" placeholder="{#company_desc_ph#|ucfirst}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][company_slogan]">{#company_slogan#|ucfirst} :</label>
                                <input type="text" class="form-control" id="content[{$id}][company_slogan]" name="content[{$id}][company_slogan]" value="{$contentData.{$id}.slogan}" size="50" placeholder="{#company_slogan_ph#|ucfirst}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content[{$id}][company_content]">{#content#|ucfirst} :</label>
                        <textarea name="content[{$id}][company_content]" id="content[{$id}][company_content]" class="form-control mceEditor">{call name=cleantextarea field=$contentData.{$id}.content}</textarea>
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
                                    <label for="content[{$id}][seo_title]">{#title#|ucfirst} :</label>
                                    <textarea class="form-control" id="content[{$id}][seo_title]" name="content[{$id}][seo_title]" cols="70" rows="3">{$contentData[{$id}].seo_title}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-ph-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][seo_desc]">Description :</label>
                                    <textarea class="form-control" id="content[{$id}][seo_desc]" name="content[{$id}][seo_desc]" cols="70" rows="3">{$contentData[{$id}].seo_desc}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            {/foreach}
        </div>
        <input type="hidden" id="data_type" name="data_type" value="text">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>