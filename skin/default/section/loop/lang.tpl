{if is_array($dataLang) && !empty($dataLang)}
    {if $domain.default_domain eq '0'}
        <link rel="canonical" href="{if $setting['ssl']['value'] eq 1}https://{else}http://{/if}{$defaultDomain}{$smarty.server.REQUEST_URI|replace:'amp/':''}" />
    {else}
        <link rel="canonical" href="{$url}{$smarty.server.REQUEST_URI|replace:'amp/':''}">
    {/if}
    {if !$amp & $amp_active}
        <link rel="amphtml" href="{$url}{if $smarty.server.REQUEST_URI}{$smarty.server.REQUEST_URI|replace:{$iso|cat:'/'}:{$iso|cat:'/amp/'}}{else}/amp/{/if}">
    {/if}
    {if is_null($smarty.get.controller)}
        <link rel="alternate" href="{$url}{if $amp}/amp/{/if}" hreflang="x-default" />
    {/if}
    {foreach $dataLang as $k => $lang}
        {if isset($hreflang) && is_array($hreflang)}
            {if isset($hreflang[$lang.id_lang])}
                {$data[$k]['url'] = "{$url}{$hreflang[$lang.id_lang]}"}
            {/if}
        {else}
        {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
            {$data[$k]['url'] = "{$url}/{$lang.iso_lang}/{if $amp}amp/{/if}{$smarty.get.controller}/"}
        {else}
            {$data[$k]['url'] = "{$url}/{$lang.iso_lang}/{if $amp}amp/{/if}"}
        {/if}
        {/if}
        {if !empty($data[$k]['url'])}
            <link rel="alternate" href="{$data[$k]['url']}" hreflang="{$lang.iso_lang}" />
        {/if}
    {/foreach}
{/if}