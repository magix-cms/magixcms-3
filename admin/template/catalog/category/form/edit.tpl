{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_cat" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_cat}" method="post" class="validate_form edit_form_extend col-ph-12 col-md-6">
        <div class="tab-content">
            {foreach $langs as $id => $iso}
                <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][name_cat]">{#title#|ucfirst} :</label>
                                <input type="text" class="form-control" id="content[{$id}][name_cat]" name="content[{$id}][name_cat]" value="{$page.content[{$id}].name_cat}" size="50" />
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="form-group">
                                <label for="content[{$id}][published_cat]">Statut</label>
                                <input id="content[{$id}][published_cat]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_cat]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published_cat} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][url_cat]">{#url_rewriting#|ucfirst}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="content[{$id}][url_cat]" name="content[{$id}][url_cat]" readonly="readonly" size="30" value="{$page.content[{$id}].url_cat}" />
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
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <label for="public-url[{$id}]">URL</label>
                                <input type="text" class="form-control public-url" data-lang="{$id}" id="public_url[{$id}]" readonly="readonly" size="50" value="{$page.content[{$id}].public_url}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="content[{$id}][content_cat]">{#content#|ucfirst} :</label>
                                <textarea name="content[{$id}][content_cat]" id="content[{$id}][content_cat]" class="form-control mceEditor">{call name=cleantextarea field=$page.content[{$id}].content_cat}</textarea>
                            </div>
                        </div>
                    </div>

                </fieldset>
            {/foreach}
        </div>
        <input type="hidden" id="id_cat" name="id" value="{$page.id_cat}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>