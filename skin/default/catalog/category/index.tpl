{extends file="catalog/index.tpl"}
{block name='body:id'}category{/block}

{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$cat.name}</h1>
            <div class="text" itemprop="text">
                {if !empty($cat.imgSrc)}
                    <figure>
                        <a href="{$cat.imgSrc.large}" class="img-zoom">
                            <img class="img-responsive" src="{$cat.imgSrc.medium}" alt="{$cat.title}" title="{$cat.title}" />
                        </a>
                    </figure>
                {/if}
                {$cat.content}
            </div>
            {if $categories}
                <h3>{#subcategories#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="catalog/loop/category.tpl" data=$categories classCol='vignette col-ph-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
            {if $products}
                <h3>{#products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="catalog/loop/product.tpl" data=$products classCol='vignette col-ph-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}