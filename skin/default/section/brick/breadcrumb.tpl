{strip}
{if !isset($catalog)}
    {assign var="catalog" value=true}
{/if}
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

{* Pages *}
{if $smarty.get.controller == 'pages'}
    {* Parent *}
    {if $pages.id_parent}
        {$bread[] = ['name' => {$parent.title},'url' => "{geturl}{$parent.url}",'title' => "{#show_page#}: {$parent.title}"]}
    {/if}
    {* /Parent *}

    {$bread[] = ['name' => {$pages.title}]}
{/if}
{* /Pages *}

{* Catalogue *}
{if $smarty.get.controller == 'catalog'}
    {if $root.name === ''}{$root.name = {#catalog#}}{/if}
    {if $cat || $product}
        {* Root *}
        {if $catalog}
            {$bread[] = ['name' => {$root.name},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}catalog/",'title' => {$root.name}]}
        {/if}

        {* Catégories *}
        {if !empty($parent)}
            {$bread[] = ['name' => {$parent.name},'url' => "{geturl}{$parent.url}",'title' => "{#show_category#}: {$parent.name}"]}
        {/if}

        {* Catégories *}
        {if !empty($cat)}
            {$bread[] = ['name' => {$cat.name}]}
        {/if}

        {* product *}
        {if !empty($product)}
            {$bread[] = ['name' => {$product.name}]}
        {/if}
    {else}
        {$bread[] = ['name' => {$root.name}]}
    {/if}
    {* /Root *}
{/if}
{* /Catalogue *}

{* Actualités *}
{if $smarty.get.controller == 'news'}
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

    {* News *}
    {if $smarty.get.id}
        {$bread[] = ['name' => {$news.title}]}
    {/if}
    {* /News *}
{/if}
{* /Actualités *}

{* Plugins *}
{if $smarty.get.controller == 'plugins'}
    {if $smarty.get.magixmod == 'contact'}
        {$bread[] = ['name' => {#contact_form#}]}
    {/if}
    {if $smarty.get.magixmod == 'gmap'}
        {$bread[] = ['name' => {#contact_form#},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}contact/",'title' => {#contact_label#}]}
        {$bread[] = ['name' => {#plan_acces#}]}
    {/if}
    {if $smarty.get.magixmod == 'about'}
        {if $smarty.get.pnum1}
            {$bread[] = ['name' => {$parent.title},'url' => "{geturl}/{getlang}/{if $amp}amp/{/if}about/",'title' => "{#show_page#}: {$parent.title}"]}
            {$bread[] = ['name' => {$page.title}]}
        {else}
            {$bread[] = ['name' => {$page.title}]}
        {/if}
    {/if}
    {if $smarty.get.magixmod == 'faq'}
        {$bread[] = ['name' => {#faq#}]}
    {/if}
{/if}
{* /Plugins *}
    {if $amp}
        {$file = "amp/section/nav/breadcrumb.tpl"}
    {else}
        {$file = "section/nav/breadcrumb.tpl"}
    {/if}
{/strip}
{include file=$file}