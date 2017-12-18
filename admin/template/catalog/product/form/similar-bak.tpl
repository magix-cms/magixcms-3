<form id="edit_product_similar" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add&product_id={$smarty.get.product_id}" method="post" class="validate_form add_form collapse in col-ph-12 col-md-6">
    <div class="row">
        <div class="col-ph-12 col-md-2">
            <div class="form-group">
                <label for="product_id">{#id#|ucfirst} {#product#}&nbsp;</label>
                <input type="text" name="product_id" id="product_id" class="form-control mygroup" placeholder="{#ph_id#}" value="" />
            </div>
        </div>
        <div class="col-ph-12 col-md-4">
            <div class="form-group">
                <label for="product">{#product#|ucfirst}&nbsp;</label>
                <div id="product" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
                    <a href="#" class="clear"><span class="fa fa-times"></span><span class="sr-only">Annuler la sélection</span></a>
                    <button data-id="product" type="button" class="btn btn-block btn-default dropdown-toggle">
                        <span class="placeholder">{#ph_product#|ucfirst}</span>
                        <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        <div class="live-filtering" data-clear="true" data-autocomplete="true" data-keys="true">
                            <label class="sr-only" for="input-pages">Rechercher dans la liste de produit</label>
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
                                    {foreach $products as $items}
                                        <li class="filter-item items" data-filter="{$items.name_p}" data-value="{$items.id_product}" data-id="{$items.id_product}">
                                            {$items.name_p}
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
        <div id="submit" class="col-ph-12 col-md-6">
            <input type="hidden" id="id_product" name="id" value="{$smarty.get.product_id}">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
        </div>
    </div>
</form>