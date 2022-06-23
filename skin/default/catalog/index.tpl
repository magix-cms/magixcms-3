{extends file="layout.tpl"}
{block name="title" nocache}{$root.seo.title}{/block}
{block name="description" nocache}{$root.seo.description}{/block}
{block name='body:id'}catalog{/block}
{block name="webType"}CollectionPage{/block}
{block name="styleSheet"}
    {$css_files = ["catalog","lightbox","slider"]}
{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            {nocache}
            <h1 itemprop="name">{$root.name}</h1>
            <div class="text" itemprop="text">
                {$root.content}
            </div>
            {/nocache}
            {if $categories}
            <p class="h2">{#categories#}</p>
            <div class="vignette-list">
                <div class="row" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-6 col-md-4' nocache}
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}