{if is_array($dataLang) && !empty($dataLang)}
    {if $display eq 'list'}
        <ul class="lang-nav list-inline">
            {foreach $dataLang as $item}
                <li>
                    <a{if $smarty.get.strLangue eq $item.iso} class="active"{/if} href="/{$item.iso}/" hreflang="{$item.iso}" title="{#go_to_version#|ucfirst}: {$item.language}">
                        {$item.iso|upper}
                    </a>
                </li>
            {/foreach}
        </ul>
    {elseif $display eq 'menu'}
        <div id="menu-language" class="dropdown">
            <button class="btn btn-box btn-default" type="button" on="tap:menu-language.toggleClass(class='open')">
                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                {if $smarty.get.strLangue}
                    {$smarty.get.strLangue|upper}
                {else}
                    {$defaultLang|upper}
                {/if}
            </button>
            <ul class="dropdown-menu">
                {foreach $dataLang as $k => $lang}
                    {if isset($hreflang) && is_array($hreflang) && isset($hreflang[$lang.id_lang])}
                        {$dataLang[$k]['url'] = "{$url}{$hreflang[$lang.id_lang]}"}
                    {else}
                        {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
                            {$dataLang[$k]['url'] = "{$url}/{$lang.iso_lang}/amp/{$smarty.get.controller}/"}
                        {else}
                            {$dataLang[$k]['url'] = "{$url}/{$lang.iso_lang}/amp/"}
                        {/if}
                    {/if}
                    <li>
                        <a href="{$dataLang[$k]['url']}">{$lang.iso_lang|upper}</a>
                    </li>
                {/foreach}
            </ul>
        </div>
    {/if}
{/if}