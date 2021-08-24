{extends file="catalog/index.tpl"}
{block name="title" nocache}{$cat.seo.title}{/block}
{block name="description" nocache}{$cat.seo.description}{/block}
{block name='body:id'}category{/block}
{block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/catalog{if $setting.mode.value !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/lightbox{if $setting.mode.value !== 'dev'}.min{/if}.css",
    "/skin/{$theme}/css/slider{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
{/block}

{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            {nocache}
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
            {/nocache}
            {if $categories}
            <p class="h2">{#subcategories#|ucfirst}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-6 col-md-4' nocache}
                </div>
            </div>
            {/if}
            {if $products}
            <p class="h2">{#products#|ucfirst}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/product.tpl" data=$products classCol='vignette col-12 col-xs-6 col-md-4' nocache}
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}