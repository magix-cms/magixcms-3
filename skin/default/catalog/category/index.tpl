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
                    {*<figure>
                        <a href="{$cat.img.large.src}" class="img-zoom" title="{$cat.img.title}" data-caption="{$cat.img.caption}">
                            <img class="img-responsive lazyload" src="{$cat.img.medium.src}" alt="{$cat.img.alt}" title="{$cat.img.title}" />
                        </a>
                        {if $cat.img.caption}
                            <figcaption>{$cat.img.caption}</figcaption>
                        {/if}
                    </figure>*}
                    <a href="{$cat.img.large.src}" class="img-zoom img-float pull-right" title="{$cat.img.title}" data-caption="{$cat.img.caption}">
                        <figure>
                            {strip}<picture>
                                <!--[if IE 9]><video style="display: none;"><![endif]-->
                                <source type="image/webp" sizes="{$cat.img.medium['w']}px" srcset="{$cat.img.medium['src_webp']} {$cat.img.medium['w']}w">
                                <source type="{$cat.img.medium.ext}" sizes="{$cat.img.medium['w']}px" srcset="{$cat.img.medium['src']} {$cat.img.medium['w']}w">
                                <!--[if IE 9]></video><![endif]-->
                                <img data-src="{$cat.img.medium['src']}" width="{$cat.img.medium['w']}" height="{$cat.img.medium['h']}" alt="{$cat.img.alt}" title="{$cat.img.title}" class="img-responsive lazyload" />
                                </picture>{/strip}
                            {if $cat.img.caption}
                                <figcaption>{$cat.img.caption}</figcaption>
                            {/if}
                        </figure>
                    </a>
                {/if}
                {$cat.content}
            </div>
            {if $categories}
                <h3>{#subcategories#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                            {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
            {if $products}
                <h3>{#products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                            {include file="catalog/loop/product.tpl" data=$products classCol='vignette col-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}