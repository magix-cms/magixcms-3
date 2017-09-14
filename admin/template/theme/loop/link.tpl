<li id="order_{$link.id_link}" class="list-group-item">
    <header>
        <span class="fa fa-arrows"></span> {$link.name_link}{* &mdash; ({$link.type_link})*}
        <div class="actions">
            <a href="#link{$link.id_link}" class="btn btn-link" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="link{$link.id_link}">
                <span class="fa fa-pencil-square-o"></span>
            </a>
            <a href="#" class="btn btn-link">
                <span class="fa fa-trash"></span>
            </a>
        </div>
    </header>
    <div id="link{$link.id_link}" class="collapse">
        <form action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" class="validate_form edit_in_list">
            {if in_array($link.type_link,array('pages','about','about_page','catalog'))}
            <div class="btn-group btn-group-justified" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="radio" name="link[{$link.id_link}][mode]" value="simple" id="simple{$link.id_link}" autocomplete="off"{if $link.mode_link === 'simple'} checked{/if}>
                    Simple
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="link[{$link.id_link}][mode]" value="dropdown" id="dropdown{$link.id_link}" autocomplete="off"{if $link.mode_link === 'dropdown'} checked{/if}>
                    Dropdown
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="link[{$link.id_link}][mode]" value="mega" id="mega{$link.id_link}" autocomplete="off"{if $link.mode_link === 'mega'} checked{/if}>
                    Megadropdown
                </label>
            </div>
            {else}
                <input type="hidden" name="link[{$link.id_link}][mode]" value="simple" />
            {/if}
            {include file="language/brick/dropdown-lang.tpl"}
            <div class="tab-content">
                {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        <div class="form-group">
                            <label for="link[{$link.id_link}][{$id}][name_link]">Texte affiché</label>
                            <input type="text" id="link[{$link.id_link}][{$id}][name_link]" name="link[{$link.id_link}][{$id}][name_link]" value="{$link.content[{$id}].name_link}" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="link[{$link.id_link}][{$id}][title_link]">Libellé (Texte au survol)</label>
                            <input type="text" id="link[{$link.id_link}][{$id}][title_link]" name="link[{$link.id_link}][{$id}][title_link]" value="{$link.content[{$id}].title_link}" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="link[{$link.id_link}][{$id}][url_link]">URL</label>
                            <input type="text" id="link[{$link.id_link}][{$id}][url_link]" name="link[{$link.id_link}][{$id}][url_link]" value="{$link.content[{$id}].url_link}" class="form-control" disabled/>
                        </div>
                    </fieldset>
                {/foreach}
            </div>
            <button type="submit" class="btn btn-main-theme">Enregistrer</button>
            <input type="hidden" name="pages_id" value="{$link['id_link']}" />
        </form>
    </div>
</li>