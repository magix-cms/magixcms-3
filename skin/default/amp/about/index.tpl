{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/pages.min.css"}{/block}
{block name='body:id'}about{/block}
{block name="webType"}{if isset($parent)}WebPage{else}AboutPage{/if}{/block}
{block name="amp-script"}
    {amp_components content=$pages.content}
{/block}
{block name='article'}
    <article class="container" id="article" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            {*<meta itemprop="name" content="{$pages.name}" />
            <h1>
                <span class="row">
                    <span class="col-ph-12 col-xs-3">{#about#|ucfirst}</span><span class="col-ph-12 col-xs-9">{$pages.name}</span>
                </span>
            </h1>*}
            <h1 itemprop="name">{$pages.name}</h1>
            {if $pages.date.register}<time datetime="{$pages.date.register}" itemprop="datePublished"></time>{/if}
            {if $pages.date.update}<time datetime="{$pages.date.update}" itemprop="dateModified"></time>{/if}
            <div class="content">
                <div itemprop="text">
                    {$pages.content}
                </div>
                {*<div class="col-ph-12 col-sm-3">
                    <nav class="child-nav">
                        <ul class="list-unstyled">
                            <li{if !isset($smarty.get.id)} class="active"{/if}><a{if isset($smarty.get.id)} itemprop="relatedLink"{/if} href="{geturl}/{getlang}/about/" title="{#show_page#}: {$root.name}">{$root.name}</a></li>
                            {if isset($pagesTree) && $pagesTree != null && !empty($pagesTree)}
                                {foreach $pagesTree as $child}
                                    <li{if $smarty.get.id == $child.id} class="active"{/if}><a{if $smarty.get.id != $child.id} itemprop="relatedLink"{/if} href="{$child.url}" title="{#show_page#}: {$child.title}">{$child.title}</a></li>
                                {/foreach}
                            {/if}
                        </ul>
                    </nav>
                </div>*}
            </div>
        {/block}
    </article>
{/block}