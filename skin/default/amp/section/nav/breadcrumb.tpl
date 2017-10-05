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
    {$bread[] = ['name' => {$hname},'url' => "{geturl}/{getlang}/amp/",'title' => {#show_home#}]}
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
    {if $cat}
        {* Root *}
        {if $catalog}
            {$bread[] = ['name' => {$root.name},'url' => "{geturl}/{getlang}/amp/catalog/",'title' => {$root.name}]}
        {/if}

        {* Catégories *}
        {if $parent}
            {$bread[] = ['name' => {$parent.name},'url' => "{geturl}{$parent.url}",'title' => "{#show_category#}: {$parent.name}"]}
        {/if}

        {* Catégories *}
        {if $cat}
            {$bread[] = ['name' => {$cat.name}]}
        {/if}

        {* product *}
        {if $product}
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
    {if $smarty.get.tag OR $smarty.get.uri_get_news}
        {$bread[] = ['name' => {#news#},'url' => "{geturl}/{getlang}/amp/{#nav_news_uri#}/",'title' => {#show_news#}]}
    {else}
        {$bread[] = ['name' => {#news#}]}
    {/if}
    {* /Root *}

    {* Tag *}
    {if $smarty.get.tag}
        {$bread[] = ['name' => "{#theme#}: {$tag.name}"]}
    {/if}
    {* /Tag *}

    {* News *}
    {if $smarty.get.uri_get_news}
        {$bread[] = ['name' => {$news.name}]}
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
        {$bread[] = ['name' => {#contact_form#},'url' => "{geturl}/{getlang}/amp/{#nav_contact_uri#}/",'title' => {#contact_label#}]}
        {$bread[] = ['name' => {#plan_acces#}]}
    {/if}
    {if $smarty.get.magixmod == 'about'}
        {if $smarty.get.pnum1}
            {$bread[] = ['name' => {$parent.title},'url' => "{geturl}/{getlang}/amp/about/",'title' => "{#show_page#}: {$parent.title}"]}
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
{/strip}
<div id="breadcrumb-container">
    {strip}{$length = $bread|count}{/strip}
    <nav id="breadcrumb" class="breadcrumb container" itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
        <ol>
        {foreach from=$bread item=breadcrumb key=i}
            {if $length > 3 && $i == 1 && $mobile}
            <li id="hellipsis">
                <div class="dropdown">
                    <amp-accordion disable-session-states>
                        <section>
                            <header>
                                <button class="btn btn-box btn-default" type="button">
                                    <span class="sr-only">{#toggle_nav#|ucfirst}</span>
                                    <span class="fa fa-ellipsis-h"></span>
                                </button>
                            </header>
                            <div id="breadcrumb-collapse">
                                <ol>
            {/if}
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                {strip}{if isset($breadcrumb.url)}
                <a href="{$breadcrumb.url}" title="{$breadcrumb.title|ucfirst}" itemprop="item">
                    <span itemprop="name">{$breadcrumb.name|ucfirst}</span>
                    <meta itemprop="position" content="{($breadcrumb@index + 1)}" />
                </a>
                {else}
                <span itemprop="item">
                    <span itemprop="name">{$breadcrumb.name|ucfirst}</span>
                    <meta itemprop="position" content="{($breadcrumb@index + 1)}" />
                </span>
                {/if}{/strip}
            </li>
            {if $length > 3 && $i == $length-2 && $mobile}
                </ol>
                </div>
                </section>
                </amp-accordion>
                </div>
            </li>
            {/if}
        {/foreach}
        </ol>
    </nav>
</div>