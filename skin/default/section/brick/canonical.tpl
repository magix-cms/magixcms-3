{if !empty($defaultDomain)}
    {*    {if $domain.default_domain eq '0' && $domain.canonical_domain eq '1'}*}
    {if isset($canonical)}
        {$uri = $canonical}
    {else}
        {$uri = $smarty.server.REQUEST_URI}
    {/if}
    <link rel="canonical" href="{if $setting['ssl']['value'] eq 1}https://{else}http://{/if}{$defaultDomain}{$uri|replace:'amp/':''}" />
    {*{else}
        {if $amp}
            <link rel="canonical" href="{$url}{$smarty.server.REQUEST_URI|replace:'amp/':''}">
        {/if}
    {/if}*}
{/if}