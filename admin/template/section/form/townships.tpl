{if !isset($required)}{$required = false}{/if}
{if isset($type) && !empty($type) && is_string($type)}
<div>
    <div class="form-group">
        <label for="{$type}_ts">{#city#|ucfirst}{if $required}&nbsp;*{/if}</label>
        <div id="{$type}_ts" class="btn-group btn-block selectpicker" data-clear="true" data-live="true">
            <a href="#" class="clear"><span class="fa fa-times"></span><span class="sr-only">Annuler la sélection</span></a>
            <button data-id="{$type}_tss" type="button" class="btn btn-block btn-default dropdown-toggle">
                <span class="placeholder">{#ph_city#|ucfirst}</span>
                <span class="caret"></span>
            </button>
            <div class="dropdown-menu">
                <div class="live-filtering" data-clear="true" data-autocomplete="true" data-keys="true">
                    <label class="sr-only" for="input-{$type}_tss">Rechercher dans la liste</label>
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-addon" id="search-{$type}_tss">
                                <span class="fa fa-search"></span>
                                <a href="#" class="fa fa-times hide filter-clear"><span class="sr-only">Effacer filtre</span></a>
                            </span>
                            <input type="text" placeholder="Rechercher dans la liste" id="input-{$type}_tss" class="form-control live-search" aria-describedby="search-{$type}_tss" tabindex="1" />
                        </div>
                    </div>
                    <div id="filter-{$type}_tss" class="list-to-filter">
                        <ul class="list-unstyled">
                            {foreach $townships as $ts}
                                <li class="filter-item items{if isset($selected) && $ts.postcode_township == $selected} selected{/if}" data-filter="{$ts.name_township} {$ts.postcode_township}" data-value="{$ts.name_township}" data-id="{$ts.postcode_township}">
                                    {$ts.name_township}&nbsp;<small>({$ts.postcode_township})</small>
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
    <div class="row">
        <div class="col-ph-12 col-sm-6">
            <div class="form-group">
                <label for="{$type}_ts">{#city#|ucfirst}</label>
                <input id="{$type}_ts" class="form-control" type="text" name="{$type}_ts" placeholder="{#ph_city#|ucfirst}" value=""{if $required} required{/if}/>
            </div>
        </div>
        <div class="col-ph-12 col-sm-6">
            <div class="form-group">
                <label for="{$type}_ts_id">{#postcode#|ucfirst}</label>
                <input id="{$type}_ts_id" class="form-control" type="text" name="{$type}_ts_id" placeholder="{#ph_postcode#|ucfirst}" value=""{if $required} required{/if}/>
            </div>
        </div>
    </div>
</div>
{/if}