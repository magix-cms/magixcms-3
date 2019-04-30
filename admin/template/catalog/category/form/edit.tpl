{include file="language/brick/dropdown-lang.tpl"}
<div class="row">
    <form id="edit_cat" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_cat}" method="post" class="validate_form edit_form_extend col-ph-12 col-md-6">
        <div class="tab-content">
            <div class="row">
                <div class="col-ph-12 col-md-2">
                    <div class="form-group">
                        <label for="parent_id">{#id#|ucfirst} {#category#}&nbsp;</label>
                        <input type="text" name="parent_id" id="parent_id" class="form-control mygroup" placeholder="{#ph_id#}" value="{$smarty.get.parent_id}" />
                    </div>
                </div>
                <div class="col-ph-12 col-md-6">
                    <div class="form-group">
                        <label for="parent">{#parent_page#|ucfirst}&nbsp;</label>
                        <div id="parent" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
                            <a href="#" class="clear"><span class="fa fa-times"></span><span class="sr-only">Annuler la sélection</span></a>
                            <button data-id="parent" type="button" class="btn btn-block btn-default dropdown-toggle">
                                <span class="placeholder">{#ph_cat#|ucfirst}</span>
                                <span class="caret"></span>
                            </button>
                            <div class="dropdown-menu">
                                <div class="live-filtering" data-clear="true" data-autocomplete="true" data-keys="true">
                                    <label class="sr-only" for="input-pages">Rechercher dans la liste</label>
                                    <div class="search-box">
                                        <div class="input-group">
                                    <span class="input-group-addon" id="search-pages">
                                        <span class="fa fa-search"></span>
                                        <a href="#" class="fa fa-times hide filter-clear"><span class="sr-only">Effacer filtre</span></a>
                                    </span>
                                            <input type="text" placeholder="Rechercher dans la liste" id="input-pages" class="form-control live-search" aria-describedby="search-pages" tabindex="1" />
                                        </div>
                                    </div>
                                    <div id="filter-pages" class="list-to-filter">
                                        <ul class="list-unstyled">
                                            {$incorrectParents=array($page.id_cat)}
                                            {foreach $pagesSelect as $items}
                                                {if in_array($items.id_parent,$incorrectParents)}
                                                    {if !in_array($items.id_cat,$incorrectParents)}{$incorrectParents[] = $page.id_cat}{/if}
                                                {elseif $items.id_cat != $page.id_cat}
                                                    <li class="filter-item items{if isset($page.id_parent) && $items.id_cat == $page.id_parent} selected{/if}" data-filter="{$items.name_cat}" data-value="{$items.id_cat}" data-id="{$items.id_cat}">
                                                        {$items.name_cat}&nbsp;<small>({$items.id_cat})</small>
                                                    </li>
                                                {/if}
                                            {/foreach}
                                        </ul>
                                        <div class="no-search-results">
                                            <div class="alert alert-warning" role="alert"><i class="fa fa-warning margin-right-sm"></i>Aucune entrée pour <strong>'<span></span>'</strong> n'a été trouvée.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-4">
                    <div class="form-group">
                        <label for="menu_cat">{#menu#}</label>
                        <input id="menu_cat" data-toggle="toggle" type="checkbox" name="menu_cat" data-on="{#visible#}" data-off="{#hidden_f#}" data-onstyle="success" data-offstyle="danger"{if $page.menu_cat} checked{/if}>
                    </div>
                </div>
            </div>
            {foreach $langs as $id => $iso}
                <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="content[{$id}][name_cat]">{#title#|ucfirst} :</label>
                                <input type="text" class="form-control" id="content[{$id}][name_cat]" name="content[{$id}][name_cat]" value="{$page.content[{$id}].name_cat}" size="50" />
                            </div>
                        </div>
                        <div class="col-ph-12 col-sm-4">
                            <div class="form-group">
                                <label for="content[{$id}][published_cat]">{#status#|ucfirst}</label>
                                <input id="content[{$id}][published_cat]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_cat]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($page) && $iso@first) || $page.content[{$id}].published_cat} checked{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-ph-12 col-sm-8">
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
                        <div class="col-ph-12 col-sm-8">
                            <div class="form-group">
                                <label for="public-url[{$id}]">URL</label>
                                <input type="text" class="form-control public-url" data-lang="{$id}" id="public_url[{$id}]" readonly="readonly" size="50" value="{$page.content[{$id}].public_url}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content[{$id}][resume_cat]">{#resume#|ucfirst} :</label>
                        <textarea name="content[{$id}][resume_cat]" id="content[{$id}][resume_cat]" class="form-control">{$page.content[{$id}].resume_cat}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="content[{$id}][content_cat]">{#content#|ucfirst} :</label>
                        <textarea name="content[{$id}][content_cat]" id="content[{$id}][content_cat]" class="form-control mceEditor">{call name=cleantextarea field=$page.content[{$id}].content_cat}</textarea>
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
                                    <label for="content[{$id}][seo_title_cat]">{#title#|ucfirst} :</label>
                                    <textarea class="form-control" id="content[{$id}][seo_title_cat]" name="content[{$id}][seo_title_cat]" cols="70" rows="3">{$page.content[{$id}].seo_title_cat}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-ph-12 col-sm-8">
                                <div class="form-group">
                                    <label for="content[{$id}][seo_desc_cat]">Description :</label>
                                    <textarea class="form-control" id="content[{$id}][seo_desc_cat]" name="content[{$id}][seo_desc_cat]" cols="70" rows="3">{$page.content[{$id}].seo_desc_cat}</textarea>
                                </div>
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