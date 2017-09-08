<form id="add_cat" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
    {include file="language/brick/dropdown-lang.tpl"}
    <div class="row">
        <div class="col-xs-12 col-md-2">
            <div class="form-group">
                <label for="parent_id">{#id#|ucfirst} {#category#}&nbsp;</label>
                <input type="text" name="parent_id" id="parent_id" class="form-control mygroup" placeholder="{#ph_id#}" value="{$smarty.get.parent_id}" />
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
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
                                    {foreach $pagesSelect as $items}
                                        <li class="filter-item items" data-filter="{$items.name_cat}" data-value="{$items.id_cat}" data-id="{$items.id_cat}">
                                            {$items.name_cat}&nbsp;{if $items.id_parent != '0'}<small>({$items.parent_cat})</small>{/if}
                                        </li>
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
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10">
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
                                    <label for="content[{$id}][published_cat]">{#status#|ucfirst}</label>
                                    <input id="content[{$id}][published_cat]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_cat]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if $page.content[{$id}].published_cat} checked{/if}>
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
                    </fieldset>
                {/foreach}
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-6">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
        </div>
    </div>
</form>