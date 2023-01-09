<div id="breadcrumb-container">
    {strip}{$length = $bread|count}{/strip}
    <nav id="breadcrumb" class="breadcrumb container" itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
        <ol>
        {foreach from=$bread item=breadcrumb key=i}
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                {strip}{if isset($breadcrumb.url)}
                <a href="{$breadcrumb.url}" title="{$breadcrumb.title|ucfirst}">
                    <span itemprop="name">{$breadcrumb.name|ucfirst}</span>
                    <meta itemprop="position" content="{($breadcrumb@index + 1)}" />
                    <meta itemprop="item" content="{$breadcrumb.url}" />
                </a>
                {else}
                <span>
                    <meta itemprop="item" content="{$url}{$smarty.server.REQUEST_URI}" />
                    <span itemprop="name">{$breadcrumb.name|ucfirst}</span>
                    <meta itemprop="position" content="{($breadcrumb@index + 1)}" />
                </span>
                {/if}{/strip}
            </li>
        {/foreach}
        </ol>
    </nav>
</div>