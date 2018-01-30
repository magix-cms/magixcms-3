{if isset($data)}
    <div class="filtering">
        <div class="filter-parent">
            <label for="input-{$dataType|substr:0:5}"><span class="fa fa-visite"></span></label>
            <div>
                <input type="text" class="form-control input-lg hint" tabindex="-1" />
                <input type="text" placeholder="Rechercher une fiche" id="input-{$dataType|substr:0:5}" class="form-control input-lg filter-by" tabindex="1" />
            </div>
            <a href="#" class="fa fa-times hide filter-clear"></a>
            <div id="filter-{$dataType|substr:0:5}">
                {*<h2 class="page-header text-muted">Résultat(s) pour '<span class="text-color-default filter-val"></span>'</h2>*}
                <div class="row">
                    <div class="product-list center-gallery-sm-4 center-gallery-md-3">
                        {include file="account/loop/sheet.tpl" data=$data dataType=$dataType}
                    </div>
                </div>
                <div class="no-search-results">
                    <div class="alert alert-warning" role="alert"><i class="fa fa-warning margin-right-sm"></i>Aucune fiche pour <strong>'<span></span>'</strong> n'a été trouvée.</div>
                </div>
            </div>
        </div>
    </div>
{/if}