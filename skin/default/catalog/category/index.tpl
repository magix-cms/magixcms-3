{extends file="catalog/index.tpl"}
{block name='body:id'}category{/block}
{block name="title"}{$cat.seo.title}{/block}
{block name="description"}{$cat.seo.description}{/block}

{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$cat.name}</h1>
            <div class="text" itemprop="text">
                {if count($cat.img) > 1}
                    <figure>
                        <a href="{$cat.img.large.src}" class="img-zoom" title="{$cat.img.title}" data-caption="{$cat.img.caption}">
                            <img class="img-responsive lazyload" src="{$cat.img.medium.src}" alt="{$cat.img.alt}" title="{$cat.img.title}}" />
                        </a>
                        {if $cat.img.caption}
                            <figcaption>{$cat.img.caption}</figcaption>
                        {/if}
                    </figure>
                {/if}
                {$cat.content}
            </div>
            {if $categories}
                <h3>{#subcategories#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
            {if $products}
                <h3>{#products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="catalog/loop/product.tpl" data=$products classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}