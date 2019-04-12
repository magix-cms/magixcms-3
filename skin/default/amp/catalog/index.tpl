{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/catalog.min.css"}{/block}
{block name='body:id'}catalog{/block}
{block name="title"}{$root.seo.title}{/block}
{block name="description"}{$root.seo.description}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            <h1 itemprop="name">{$root.name}</h1>
            <div itemprop="text">
                {amp_content content=$root.content}
            </div>
            {if $categories}
            <div class="vignette-list">
                <div class="section-block">
                    <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                        {include file="amp/catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                    </div>
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}