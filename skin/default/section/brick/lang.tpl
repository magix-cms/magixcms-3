<div class="dropdown">
    <a class="dropdown-toggle btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
        <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
        {if $smarty.get.strLangue}
            {$smarty.get.strLangue|upper}
        {else}
            {$defaultLang.iso_lang|upper}
        {/if}
    </a>
    <ul class="dropdown-menu" aria-labelledby="menu-language">
        {foreach $dataLang as $k => $lang}
            {if isset($hreflang) && is_array($hreflang) && isset($hreflang[$lang.id_lang])}
                {$dataLang[$k]['url'] = "{geturl}{$hreflang[$lang.id_lang]}"}
            {else}
                {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
                    {$dataLang[$k]['url'] = "{geturl}/{$lang.iso_lang}/{$smarty.get.controller}/"}
                {else}
                    {$dataLang[$k]['url'] = "{geturl}/{$lang.iso_lang}/"}
                {/if}
            {/if}
            <li>
                <a href="{$dataLang[$k]['url']}">{$lang.iso_lang|upper}</a>
            </li>
        {/foreach}
    </ul>
</div>