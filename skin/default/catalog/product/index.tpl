{extends file="catalog/index.tpl"}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name="title"}{seo_rewrite conf=['level'=>'record','type'=>'title','default'=>{$product.name}] parent={$parent.name} record={$product.name}}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'record','type'=>'description','default'=>{$product.resume}] parent={$parent.name} record={$product.name}}{/block}
{block name='article'}
    {capture name="contact"}
        <form action="/{$lang}/contact/" method="get" class="interested-form">
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
            {*{$product.imgs|var_dump}*}
            {if count($product.imgs) > 1}
                {include file="catalog/loop/gallery.tpl"}
            {elseif count($product.imgs) > 0}
                {$img = $product.imgs[0]}
                <figure{if $img.img.medium} itemprop="image" itemscope itemtype="http://schema.org/ImageObject"{/if}>
                    {if $img.img.medium}
                        <meta itemprop="contentUrl" content="{$img.img.large}" />
                        <a href="{$img.img.large.src}" class="img-zoom" title="{$product.name}" itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                            <img src="{$product.img_default}" data-src="{$img.img.medium.src}" alt="{$product.name}" class="img-responsive lazy" itemprop="contentUrl"{if $img.img.medium.crop === 'adaptative'} width="{$img.img.medium.w}" height="{$img.img.medium.h}"{/if}/>
                        </a>
                    {else}
                        <img class="img-responsive" src="{$product.img_default}" alt="{$product.name}" />
                    {/if}
                </figure>
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
                            {include file="catalog/loop/product.tpl" data=$product.associated classCol='vignette col-12 col-xs-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}

{block name="foot"}
    {strip}{capture name="scriptVendor"}
        /min/?f=skin/{$theme}/js/vendor/smooth-gallery.min.js
    {/capture}
        {script src=$smarty.capture.scriptVendor concat=$concat type="javascript"}{/strip}
{/block}