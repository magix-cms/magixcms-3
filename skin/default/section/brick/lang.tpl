<div class="dropdown">
    <a class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        {if $smarty.get.strLangue}
            {$smarty.get.strLangue|upper}
        {else}
            {$defaultLang.iso|upper}
        {/if}
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" aria-labelledby="menu-language">
        {foreach $dataLang as $k => $lang}
            {if isset($hreflang) && is_array($hreflang) && isset($hreflang[$lang.id_lang])}
                {$dataLang[$k]['url'] = "{geturl}{$hreflang[$lang.id_lang]}"}
            {else}
                {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
                    {$dataLang[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/{$smarty.get.controller}/"}
                {else}
                    {$dataLang[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/"}
                {/if}
            {/if}
            <li>
                <a href="{$dataLang[$k]['url']}">{$lang.iso_lang|upper}</a>
            </li>
        {/foreach}
    </ul>
</div>