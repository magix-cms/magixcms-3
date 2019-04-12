{extends file="amp/catalog/index.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/catalog.min.css"}{/block}
{block name='body:id'}category{/block}
{block name="title"}{$cat.seo.title}{/block}
{block name="description"}{$cat.seo.description}{/block}
{block name="amp-script"}
    {if $cat.imgSrc.large}
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        {amp_components content=$cat.content image=false}
    {else}
        {amp_components content=$cat.content}
    {/if}
{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage">
        {block name='article:content'}
            <h1 itemprop="name">{$cat.name}</h1>
            <div class="text" itemprop="text">
                {if !empty($cat.img)}
                <figure>
                    <amp-img on="tap:lightbox1"
                             role="button"
                             tabindex="0"
                             alt="{$cat.img.alt}"
                             title="{$cat.img.title}"
                             src="{$cat.img.medium['src_webp']}"
                             width="{$cat.img.medium['w']}"
                             height="{$cat.img.medium['h']}"
                             layout="responsive">
                        <amp-img on="tap:lightbox1"
                                 role="button"
                                 tabindex="0"
                                 alt="{$cat.img.alt}"
                                 fallback
                                 title="{$cat.img.title}"
                                 src="{$cat.img.medium['src']}"
                                 width="{$cat.img.medium['w']}"
                                 height="{$cat.img.medium['h']}"
                                 layout="responsive">
                        </amp-img>
                    </amp-img>
                    <figcaption class="hidden">{$cat.img.caption}</figcaption>
                </figure>
                <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
                {/if}
                {amp_content content=$cat.content}
            </div>
            {if $categories}
                <h3>{#subcategories#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                            {include file="amp/catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
            {if $products}
                <h3>{#products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                            {include file="amp/catalog/loop/product.tpl" data=$products classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}