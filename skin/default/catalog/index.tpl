{extends file="layout.tpl"}
{block name='body:id'}catalog{/block}
{block name="title"}{$root.seo.title}{/block}
{block name="description"}{$root.seo.description}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            <h1 itemprop="name">{$root.name}</h1>
            <div class="text" itemprop="text">
                {$root.content}
            </div>
            {if $categories}
            <p class="h2">{#categories#}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-4 col-xs-3 col-sm-4 col-md-th col-lg-4 col-xl-3'}
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}