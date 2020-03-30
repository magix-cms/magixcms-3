{extends file="catalog/index.tpl"}
{block name='body:id'}category{/block}
{block name="title"}{$cat.seo.title}{/block}
{block name="description"}{$cat.seo.description}{/block}

{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            <h1 itemprop="name">{$cat.name}</h1>
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
            {if $categories}
            <p class="h2">{#subcategories#|ucfirst}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-4 col-xs-3 col-sm-4 col-md-th col-lg-4 col-xl-3'}
                </div>
            </div>
            {/if}
            {if $products}
            <p class="h2">{#products#|ucfirst}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/product.tpl" data=$products classCol='vignette col-4 col-xs-3 col-sm-4 col-md-th col-lg-4 col-xl-3'}
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}