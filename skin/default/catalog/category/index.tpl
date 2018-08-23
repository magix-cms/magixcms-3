{extends file="catalog/index.tpl"}
{block name='body:id'}category{/block}
{block name="title"}{seo_rewrite conf=['level'=>'parent','type'=>'title','default'=>{$cat.name}] parent={$cat.name}}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'parent','type'=>'description','default'=>{$cat.resume}] parent={$cat.name}}{/block}

{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$cat.name}</h1>
            <div class="text" itemprop="text">
                {if count($item.img) > 1}
                    <figure>
                        <a href="{$cat.img.large.src}" class="img-zoom">
                            <img class="img-responsive lazy" src="{$cat.img.default}" data-src="{$cat.img.medium.src}" alt="{$cat.title}" title="{$cat.title}" {if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>
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