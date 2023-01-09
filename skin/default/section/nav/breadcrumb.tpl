<div id="breadcrumb-container">
    {if count($breadcrumbs) > 0 }
    {if $icon }{$breadcrumbs[0]['name'] = "<i class=\"material-icons ico ico-{$icon}\"></i><span class=\"hidden\">{$breadcrumbs[0]['name']}</span>"}{/if}
    <nav id="breadcrumb" class="breadcrumb container" itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
        <ol>
        {foreach $breadcrumbs as $breadcrumb}
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                {strip}{if isset($breadcrumb.url)}
                <a href="{$url}{$breadcrumb.url}" title="{$breadcrumb.title}">
                    <span itemprop="name">{$breadcrumb.name}</span>
                    <meta itemprop="position" content="{($breadcrumb@index + 1)}" />
                    <meta itemprop="item" content="{$url}{$breadcrumb.url}" />
                </a>
                {else}
                <span>
                    <meta itemprop="item" content="{$url}{$smarty.server.REQUEST_URI}" />
                    <span itemprop="name">{$breadcrumb.name}</span>
                    <meta itemprop="position" content="{($breadcrumb@index + 1)}" />
                </span>
                {/if}{/strip}
            </li>
        {/foreach}
        </ol>
    </nav>
    {/if}
</div>