{extends file="catalog/index.tpl"}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name="title"}{seo_rewrite conf=['level'=>'record','type'=>'title','default'=>{$product.name}] parent={$parent.name} record={$product.name}}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'record','type'=>'description','default'=>{$product.resume}] parent={$parent.name} record={$product.name}}{/block}
{block name='article'}
    {capture name="contact"}
        <form action="/{getlang}/contact/" method="get" class="interested-form">
            <fieldset class="text-center">
                {*<p class="lead">{#interested_in#} {$product.name}&thinsp;?</p>*}
                <p>
                    <input type="hidden" name="moreinfo" value="{$product.name}"/>
                    {*<button id="more-info" type="submit" class="btn btn-box btn-invert btn-main-theme">{#contact_form#|ucfirst}</button>*}
                    <button id="more-info" type="submit" class="btn btn-box btn-invert btn-main-theme">{#interested_in#} {$product.name}&thinsp;?</button>
                </p>
            </fieldset>
        </form>
    {/capture}
    <article class="catalog container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Series">
        {block name='article:content'}
            <header>
                <h1 itemprop="name">{$product.name}</h1>
                <span class="ref">{$product.reference}</span>
                <span class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{$product.price|round:2|number_format:2:',':' '|decimal_trim:','}</span> <span itemprop="priceCurrency" content="EUR">€</span> TTC
                </span>
            </header>
            <div itemprop="category" itemscope itemtype="http://schema.org/Series">
                <meta itemprop="name" content="{$parent.name}">
                <meta itemprop="url" content="{$parent.url}">
            </div>
            {if !$product.img.default}
                {if count($product.img) > 1}
                    {include file="catalog/loop/gallery.tpl"}
                {elseif count($product.img) > 0}
                    {$img = $product.img[0]}
                    <figure{if $img.imgSrc.medium} itemprop="image" itemscope itemtype="http://schema.org/ImageObject"{/if}>
                        {if $img.imgSrc.medium}
                            <meta itemprop="contentUrl" content="{$img.imgSrc.large}" />
                            <a href="{$img.imgSrc.large}" class="img-zoom" title="{$product.name}" itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                                <img src="{$img.imgSrc.medium}" alt="{$product.name}" class="img-responsive" itemprop="contentUrl"/>
                            </a>
                        {else}
                            <img class="img-responsive" src="/skin/{template}/img/catalog/product-default.png" alt="{$product.name}" />
                        {/if}
                    </figure>
                {/if}
            {/if}
            <div class="text" itemprop="description">
                {$product.content}
            </div>
            <div class="row-center">
                {$smarty.capture.contact}
            </div>
            {if $product.associated}
                <h3>{#similar_products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="catalog/loop/product.tpl" data=$product.associated classCol='vignette col-ph-12 col-xs-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}

{block name="foot"}
    {strip}{capture name="scriptVendor"}
        /min/?f=skin/{template}/js/vendor/smooth-gallery.min.js
    {/capture}
        {script src=$smarty.capture.scriptVendor concat=$concat type="javascript"}{/strip}
{/block}