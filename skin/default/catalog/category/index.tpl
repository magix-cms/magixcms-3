{extends file="catalog/index.tpl"}
{block name="title" nocache}{$cat.seo.title}{/block}
{block name="description" nocache}{$cat.seo.description}{/block}
{block name='body:id'}category{/block}
{block name="styleSheet" nocache}
    {$css_files = ["catalog","lightbox","slider"]}
{/block}

{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            {nocache}
            {if !empty($cat.long_name)}<meta itemprop="name" content="{$cat.name}">{/if}
            <h1 itemprop="{if !empty($cat.long_name)}alternateName{else}name{/if}">{if !empty($cat.long_name)}{$cat.long_name}{else}{$cat.name}{/if}</h1>
            <div class="text clearfix" itemprop="text">
                {if isset($cat.img.name)}
                <a href="{$cat.img.large.src}" class="img-zoom img-float float-right" title="{$cat.img.title}" data-caption="{$cat.img.caption}">
                    <figure>
                        {include file="img/img.tpl" img=$cat.img lazy=true}
                        {if $cat.img.caption}
                        <figcaption>{$cat.img.caption}</figcaption>
                        {/if}
                    </figure>
                </a>
                {/if}
                {$cat.content}
            </div>
            {/nocache}
            {if $categories}
                <p class="h2">{#categories#}</p>
                <div class="list-grid category-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {*{include file="catalog/loop/category.tpl" data=$categories classCol='vignette' nocache}*}
                    {include file="catalog/loop/category-grid.tpl" data=$categories classCol='category-card' nocache}
                </div>
            {/if}
            {if $products}
                <p class="h2">{#products#|ucfirst}</p>
                <div class="list-grid product-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {*{include file="catalog/loop/product.tpl" data=$products classCol='vignette' nocache}*}
                    {include file="catalog/loop/product-grid.tpl" data=$products classCol='product-card' nocache}
                </div>
            {/if}
        {/block}
    </article>
{/block}