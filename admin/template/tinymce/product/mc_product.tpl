{extends file="tinymce/layout.tpl"}
{block name="head:title"}Search Products{/block}
{block name="head:script"}<script type="text/javascript" src="/{baseadmin}/template/tinymce/product/mc_product.js"></script>{/block}
{block name="main:content"}
    <div class="container">
        <div id="template-container" class="row">
            <form action="#" class="col-ph-12 col-md-6 col-lg-4 validate_form">
                <div class="form-group">
                    <div class="dropdown dropdown-lang">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {foreach $langs as $id => $iso}{if $iso@first}{$default = $id}{break}{/if}{/foreach}
                            <span class="lang">{$langs[$default]}</span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            {foreach $langs as $id => $iso}
                                <li role="presentation"{if $iso@first} class="active"{/if}>
                                <a data-target="#page-lang-{$id}" aria-controls="page-lang-{$id}" role="tab" data-toggle="tab">{$iso}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="page-lang-{$id}">
                        <h2>Ajouter un lien</h2>
                        <div class="form-group">
                            <div id="{$iso}product" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
                                <a href="#" class="clear"><span class="fas fa-times"></span><span class="sr-only">Annuler la sélection</span></a>
                                <button data-id="parent" type="button" class="btn btn-block btn-default dropdown-toggle">
                                    <span class="placeholder">Choississez un lien à ajouter</span>
                                    <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu">
                                    <div class="live-filtering" data-clear="true" data-autocomplete="true" data-keys="true">
                                        <label class="sr-only" for="input-product">Rechercher dans la liste</label>
                                        <div class="search-box">
                                            <div class="input-group">
													<span class="input-group-addon" id="search-product">
														<span class="fa fa-search"></span>
														<a href="#" class="fa fa-times hide filter-clear"><span class="sr-only">Effacer filtre</span></a>
													</span>
                                                <input type="text" placeholder="Rechercher dans la liste" id="input-product" class="form-control live-search" aria-describedby="search-product" tabindex="1" />
                                            </div>
                                        </div>
                                        <div id="filter-product" class="list-to-filter tree-display">
                                            <ul class="list-unstyled">
                                            {foreach $products[$id] as $link}
                                                {if !isset($g) || $g !== $link.id_parent}{$g = $link.id_parent}
                                                    {if !$link@first}</ul></li>{/if}
                                                <li class="optgroup">
                                                    <span class="optgroup-header">{$link.name_parent} (id: {$link.id_parent})</span>
                                                    <ul class="list-unstyled">
                                                {/if}
                                                    <li class="filter-item items" data-filter="{$link['name_product']}" data-value="{$link['id_product']}" data-id="{$link['id_product']}">{$link['name_product']|ucfirst}</li>
                                                {if $link@last}</ul></li>{/if}
                                            {/foreach}
                                            </ul>
                                            <div class="no-search-results">
                                                <div class="alert alert-warning" role="alert"><i class="fa fa-warning margin-right-sm"></i>Aucune entrée pour <strong>'<span></span>'</strong> n'a été trouvée.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="{$iso}product_id" id="{$iso}product_id" class="form-control product_id" value="" data-lang="{$iso}"/>
                        </div>
                    </fieldset>
                    {/foreach}
                </div>
            </form>
            <form id="product-link" action="#" method="post" class="col-ph-12 col-md-6 col-lg-4 validate_form collapse">
                <div class="form-group">
                    <label for="text">Texte affiché</label>
                    <input type="text" id="text" class="form-control required" placeholder="Texte affiché" required/>
                </div>
                <div class="form-group">
                    <label for="title">Libellé du lien</label>
                    <input type="text" id="title" class="form-control required" placeholder="Texte au survol" required/>
                </div>
                <div class="form-group">
                    <label for="url">URL du lien</label>
                    <input type="text" id="url" class="form-control required" value="" readonly required/>
                </div>
                <div class="form-group">
                    <label for="blank">Ouvrir le lien dans un nouvel onglet</label>
                    <div class="switch">
                        <input type="checkbox" id="blank" class="switch-native-control" />
                        <div class="switch-bg">
                            <div class="switch-knob"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-main-theme">Insérer le lien</button>
            </form>
        </div>
    </div>
{/block}