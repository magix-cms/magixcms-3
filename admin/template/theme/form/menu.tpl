<div class="row">
    <form action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" class="col-ph-12 col-md-6 col-lg-4 validate_form add_to_ullist">
        <fieldset>
            <h2>Ajouter un lien</h2>
            <div class="form-group">
                <label for="type_link">Lien</label>
                <select name="type" id="type_link" class="form-control has-optional-fields">
                    <option value="home">Accueil</option>
                    <option value="pages" class="optional-field" data-target="#specific" data-get="pages" data-appendto="#pages">Pages</option>
                    <option value="about">À propos (Root)</option>
                    <option value="about_page" class="optional-field" data-target="#specific" data-get="about_page" data-appendto="#pages">Pages à propos</option>
                    <option value="catalog">Catalogue (Root)</option>
                    <option value="category" class="optional-field" data-target="#specific" data-get="category" data-appendto="#pages">Catégorie</option>
                    <option value="news">Actualités (Root)</option>
                    <option value="plugin" class="optional-field" data-target="#specific" data-get="plugin" data-appendto="#pages">plugin</option>
                </select>
                <div id="specific" class="additional-fields collapse">
                    <div class="form-group">
                        <div id="pages" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
                            <a href="#" class="clear"><span class="fa fa-times"></span><span class="sr-only">Annuler la sélection</span></a>
                            <button data-id="parent" type="button" class="btn btn-block btn-default dropdown-toggle">
                                <span class="placeholder">Choississez un lien à ajouter</span>
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
                                    <div id="filter-pages" class="list-to-filter tree-display">
                                        <ul class="list-unstyled">
                                        </ul>
                                        <div class="no-search-results">
                                            <div class="alert alert-warning" role="alert"><i class="fa fa-warning margin-right-sm"></i>Aucune entrée pour <strong>'<span></span>'</strong> n'a été trouvée.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="pages_id" id="pages_id" class="form-control mygroup" value="" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-main-theme" type="submit"><span class="fa fa-plus"></span> Ajouter</button>
            </div>
        </fieldset>
    </form>
    <div id="link-list" class="col-ph-12 col-md-6 col-lg-4">
        <h2>Menu</h2>
        <ul id="table-link" class="list-group sortable" role="tablist">
            {foreach $links as $link}
                {include file="theme/loop/link.tpl"}
            {/foreach}
        </ul>
        <p class="no-entry alert alert-info{if {$links|count}} hide{/if}">
            <span class="fa fa-info"></span> Il n'y a aucun lien dans votre menu pour le moment.
        </p>
    </div>
</div>