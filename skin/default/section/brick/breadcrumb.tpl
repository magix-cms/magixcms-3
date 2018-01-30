{strip}
{assign var=bread value=array()}

{* Home *}
{if $icon}
    {$hname = "<i class=\"material-icons\">{$icon}</i>"}
{else}
    {$hname = {#home#}}
{/if}
{if isset($smarty.get.controller) && $smarty.get.controller != 'home'}
    {$bread[] = ['name' => {$hname},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}",'title' => {#show_home#}]}
{else}
    {$bread[] = ['name' => {$hname}]}
{/if}
{* /Home *}

{switch $smarty.get.controller}
{case 'about'}
{* About *}
{if $root.name === ''}{$root.name = {#catalog#}}{/if}
{* Root *}
{if $smarty.get.id}
    {$bread[] = ['name' => {$root.name},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}about/",'title' => {$root.name}]}
{else}
    {$bread[] = ['name' => {$root.name}]}
{/if}
{* /Root *}

{case 'pages' break}
{* Pages *}
{* Parent *}
{if $pages.id_parent}
    {$bread[] = ['name' => {$parent.title},'url' => "{geturl}{$parent.url}",'title' => "{#show_page#}: {$parent.title}"]}
{/if}
{* /Parent *}
{if $smarty.get.id}
    {$bread[] = ['name' => {$pages.title}]}
{/if}
{* /Pages *}

{case 'catalog' break}
{* Catalog *}
{if $root.name === ''}{$root.name = {#catalog#}}{/if}
{* Root *}
{if $cat || $product}
    {$bread[] = ['name' => {$root.name},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}catalog/",'title' => {$root.name}]}
{else}
    {$bread[] = ['name' => {$root.name}]}
{/if}
{* /Root *}

{* Parent category *}
{if !empty($parent)}
    {$bread[] = ['name' => {$parent.name},'url' => "{geturl}{$parent.url}",'title' => "{#show_category#}: {$parent.name}"]}
{/if}

{* Categories *}
{if !empty($cat)}
    {$bread[] = ['name' => {$cat.name}]}
{/if}

{* product *}
{if !empty($product)}
    {$bread[] = ['name' => {$product.name}]}
{/if}
{* /Catalog *}

{case 'news' break}
{* News *}
{* Root *}
{if $smarty.get.id || $smarty.get.tag || $smarty.get.date || $smarty.get.year}
    {$bread[] = ['name' => {#news#},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}news/",'title' => {#show_news#}]}
{else}
    {$bread[] = ['name' => {#news#}]}
{/if}
{* /Root *}

{* Tag *}
{if $smarty.get.tag}
    {$bread[] = ['name' => "{#theme#}: {$tag.name}"]}
{/if}
{* /Tag *}

{* Date *}
{if !isset($smarty.get.id)}
    {if $smarty.get.date}
        {$bread[] = ['name' => "{#date#}: {$smarty.get.date|date_format:'%e %B %Y'}"]}
    {elseif $smarty.get.month}
        {$bread[] = ['name' => "{#month#}: {$monthName} {$smarty.get.year}"]}
    {elseif $smarty.get.year}
        {$bread[] = ['name' => "{#year#}: {$smarty.get.year}"]}
    {/if}
{/if}
{* /Date *}

{* Topicality *}
{if $smarty.get.id}
    {$bread[] = ['name' => {$news.title}]}
{/if}
{* /Topicality *}
{* /News *}

{default}
{if isset($smarty.get.controller) && $smarty.get.controller != 'home'}
    {if isset($breadplugin) && !empty($breadplugin)}
        {$bread = array_merge($bread,$breadplugin)}
    {else}
        {$bread[] = ['name' => {#$smarty.get.controller#}]}
    {/if}
{/if}
{/switch}
{* /Plugins *}
    {if $amp}
        {$file = "amp/section/nav/breadcrumb.tpl"}
    {else}
        {$file = "section/nav/breadcrumb.tpl"}
    {/if}
{/strip}
{include file=$file}