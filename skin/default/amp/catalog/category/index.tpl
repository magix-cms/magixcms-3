{extends file="amp/catalog/index.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/catalog.min.css"}{/block}
{block name='body:id'}category{/block}
{block name='article'}
    <article class="catalog container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <h1 itemprop="name">{$cat.name}</h1>
            <div itemprop="text">
                {amp_content content=$cat.content}
            </div>
            {if $categories}
                <h3>Sous-catégories</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="amp/catalog/loop/category.tpl" data=$categories classCol='vignette col-ph-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
            {if $products}
                <h3>Produits</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="amp/catalog/loop/product.tpl" data=$products classCol='vignette col-ph-12 col-xs-8 col-sm-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}