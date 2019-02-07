{extends file="catalog/index.tpl"}
{if $product.long_name !== ''}{$product.name = $product.long_name}{/if}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name="title"}{$product.seo.title}{/block}
{block name="description"}{$product.seo.description}{/block}
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
    <article class="catalog container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Product">
        {block name='article:content'}
            {if $product.long_name !== ''}<meta itemprop="name" content="{$product.short_name}">{/if}
            <header>
                <h1 itemprop="{if $product.long_name !== ''}alternateName{else}name{/if}">{$product.name}</h1>
                {if $product.reference}<span class="ref">{#product_ref#}&nbsp;{$product.reference}</span>{/if}
                <span class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{$product.price|round:2|number_format:2:',':' '|decimal_trim:','}</span> <span itemprop="priceCurrency" content="EUR">€</span> TTC
                </span>
            </header>
            <div itemprop="category" itemscope itemtype="http://schema.org/Series">
                <meta itemprop="name" content="{$parent.name}">
                <meta itemprop="url" content="{$parent.url}">
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-xl-4">
                    {if count($product.imgs) > 1}
                        {include file="catalog/loop/gallery.tpl"}
                    {elseif count($product.imgs) > 0}
                        {$img = $product.imgs[0]}
                        <figure{if $img.img.medium} itemprop="image" itemscope itemtype="http://schema.org/ImageObject"{/if}>
                            {if $img.img.medium}
                                <meta itemprop="contentUrl" content="{$img.img.large}" />
                                <a href="{$img.img.large.src}" class="img-zoom" title="{$img.img.title}" data-caption="{$img.img.caption}" itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                                    <img{* src="{$product.img_default}"*} data-src="{$img.img.medium.src}" alt="{$img.img.alt}" class="img-responsive lazyload" itemprop="contentUrl"{if $img.img.medium.crop === 'adaptative'} width="{$img.img.medium.w}" height="{$img.img.medium.h}"{/if}/>
                                </a>
                            {else}
                                <img class="img-responsive" src="{$product.img_default}" alt="{$product.seo.title}" />
                            {/if}
                            {if $img.caption}
                                <figcaption>{$img.caption}</figcaption>
                            {/if}
                        </figure>
                    {/if}
                </div>
                <div class="col-12 col-sm-6 col-xl-8">
                    <div class="text" itemprop="description">
                        {$product.content}
                    </div>
                    <div class="row-center">
                        {$smarty.capture.contact}
                    </div>
                </div>
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