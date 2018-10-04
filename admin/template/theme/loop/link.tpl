<li id="link_{$link.id_link}" class="panel list-group-item">
    <header role="tab">
        <span class="fas fa-arrows-alt"></span> {$link.name_link|ucfirst}
        <div class="actions">
            <a href="#link{$link.id_link}" class="btn btn-link" role="button" data-toggle="collapse" data-parent="#table-link" aria-expanded="false" aria-controls="link{$link.id_link}">
                <span class="fas fa-pen"></span>
            </a>
            <a href="#" class="btn btn-link action_on_record modal_action" data-id="{$link.id_link}" data-target="#delete_modal" data-controller="theme" data-sub="link">
                <span class="fas fa-trash"></span>
            </a>
        </div>
    </header>
    <div id="link{$link.id_link}" class="collapse" role="tabpanel">
        <form action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=editlink&amp;edit={$link.id_link}" class="validate_form edit_in_list">
            {if in_array($link.type_link,array('home','pages','about','about_page','catalog','category')) || ($link.mode_opt && is_array($link.mode_opt))}
            <div class="form-group">
                <div class="btn-group btn-group-justified" data-toggle="buttons">
                    {if !$link.mode_opt || $link.mode_opt && in_array('simple', $link.mode_opt)}
                    <label class="btn btn-default{if $link.mode_link === 'simple'} active{/if}">
                        <input type="radio" name="link[{$link.id_link}][mode]" value="simple" id="simple{$link.id_link}" autocomplete="off"{if $link.mode_link === 'simple'} checked{/if}>
                        Simple
                    </label>
                    {/if}
                    {if !$link.mode_opt || $link.mode_opt && in_array('dropdown', $link.mode_opt)}
                    <label class="btn btn-default{if $link.mode_link === 'dropdown'} active{/if}">
                        <input type="radio" name="link[{$link.id_link}][mode]" value="dropdown" id="dropdown{$link.id_link}" autocomplete="off"{if $link.mode_link === 'dropdown'} checked{/if}>
                        Dropdown
                    </label>
                    {/if}
                    {if !$link.mode_opt || $link.mode_opt && in_array('mega', $link.mode_opt)}
                    <label class="btn btn-default{if $link.mode_link === 'mega'} active{/if}">
                        <input type="radio" name="link[{$link.id_link}][mode]" value="mega" id="mega{$link.id_link}" autocomplete="off"{if $link.mode_link === 'mega'} checked{/if}>
                        Megadropdown
                    </label>
                    {/if}
                </div>
            </div>
            {else}
                <input type="hidden" name="link[{$link.id_link}][mode]" value="simple" />
            {/if}
            <div class="form-group">
                <div class="dropdown dropdown-lang">
                    <button id="dp-lang-{$link.id_link}" class="btn btn-default dropdown-toggle{if $custom_class} {$custom_class}{/if}" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        {foreach $langs as $id => $iso}
                            {if $iso@first}{$default = $id}{break}{/if}
                        {/foreach}
                        <span class="lang">{$langs[$default]}</span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-describedby="dp-lang-{$link.id_link}" role="tablist">
                        {foreach $langs as $id => $iso}
                            <li role="presentation"{if $iso@first} class="active"{/if}>
                                <a data-target="#l{$link.id_link}-lang-{$id}" aria-controls="lang-{$id}" role="tab" data-toggle="tab">{$iso}</a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                {foreach $langs as $id => $iso}
                <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="l{$link.id_link}-lang-{$id}">
                    {if $link.content[{$id}].active_link || in_array($link.type_link,array('home','about','catalog','plugin'))}
                    <div class="form-group">
                        <label for="link[{$link.id_link}][{$id}][name_link]">Texte affiché</label>
                        <input type="text" id="link[{$link.id_link}][{$id}][name_link]" name="link[{$link.id_link}][content][{$id}][name_link]" value="{$link.content[{$id}].name_link}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="link[{$link.id_link}][{$id}][title_link]">Libellé (Texte au survol)</label>
                        <input type="text" id="link[{$link.id_link}][{$id}][title_link]" name="link[{$link.id_link}][content][{$id}][title_link]" value="{$link.content[{$id}].title_link}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="link[{$link.id_link}][{$id}][url_link]">URL</label>
                        <input type="text" id="link[{$link.id_link}][{$id}][url_link]" name="link[{$link.id_link}][content][{$id}][url_link]" value="{$link.content[{$id}].url_link}" class="form-control" disabled/>
                    </div>
                    {else}
                    <div class="alert alert-info">
                        <p><span class="fa fa-info"></span> La page n'est pas active dans cette langue</p>
                    </div>
                    {/if}
                </fieldset>
                {/foreach}
            </div>
            <input type="hidden" name="pages_id" value="{$link['id_link']}" />
            <button class="btn btn-main-theme" type="submit">Enregistrer</button>
            <button class="btn btn-link text-success hide" type="button"><span class="fa fa-check"></span>&nbsp;{#saved#|ucfirst}</button>
        </form>
    </div>
</li>