{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_page" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=content&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-8">
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-8">
                                    <div class="form-group">
                                        <label for="content[{$id}][name_page]">{#title#|ucfirst} *:</label>
                                        <input type="text" class="form-control" id="content[{$id}][name_page]" name="content[{$id}][name_page]" value="{$pages.content[{$id}].name_page}" size="50" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="content[{$id}][published_page]">Statut</label>
                                        <input id="content[{$id}][published_page]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_page]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($pages) && $iso@first) || $pages.content[{$id}].published_page} checked{/if}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="content[{$id}][content_page]">{#content#|ucfirst} :</label>
                                        <textarea name="content[{$id}][content_page]" id="content[{$id}][content_page]" class="form-control mceEditor">{call name=cleantextarea field=$pages.content[{$id}].content_page}</textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    {/foreach}
                </div>
            </div>
        </div>
        <input type="hidden" id="id_page" name="id" value="{$pages.id_page}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>