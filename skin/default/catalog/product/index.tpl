{extends file="catalog/index.tpl"}
{if !empty($product.long_name)}{$product.name = $product.long_name}{/if}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name="title"}{$product.seo.title}{/block}
{block name="description"}{$product.seo.description}{/block}
{block name='article'}
    {capture name="contact"}
        <form action="/{$lang}/contact/" method="get" class="interested-form">
            <fieldset>
                <p class="text-center">
                    <input type="hidden" name="moreinfo" value="{$product.name}"/>
                    <button id="more-info" type="submit" class="btn btn-box btn-main">{#interested_in#} {$product.name}&thinsp;?</button>
                </p>
            </fieldset>
        </form>
    {/capture}
    <article class="catalog container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Product">
        {block name='article:content'}
            {if $product.long_name !== ''}<meta itemprop="name" content="{$product.short_name}">{/if}
            {*<header>*}
                <h1 itemprop="{if $product.long_name !== ''}alternateName{else}name{/if}">{$product.name}</h1>
                {if $product.reference}<span class="ref">{#product_ref#}&nbsp;{$product.reference}</span>{/if}
                {if $product.price !== 0 && $setting.price_display.value === 'tinc'}
                    {$price = $product.price * (1 + ($setting.vat_rate.value/100))}
                {else}
                    {$price = $product.price}
                {/if}
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price" content="{$price|round:2|number_format:2:',':' '|decimal_trim:','}"></span><span itemprop="priceCurrency" content="EUR"></span>
                </span>
            {*</header>*}
            <div itemprop="category" itemscope itemtype="http://schema.org/Series">
                <meta itemprop="name" content="{$parent.name}">
                <meta itemprop="url" content="{$parent.url}">
            </div>
            <div class="row row-center">
                <div class="col-4 col-md-5 col-xl-4">
                    {include file="img/loop/gallery.tpl" imgs=$product.imgs}
                </div>
                <div class="col-4 col-md-5 col-lg-7 col-xl-8">
                    {strip}{if $product.price !== 0}<div class="price">{$price|round:2|number_format:2:',':' '|decimal_trim:','}&nbsp;€&nbsp;{if $setting.price_display.value === 'tinc'}{#tax_included#}{else}{#tax_excluded#}{/if}</div>{/if}{/strip}
                    <div class="text" itemprop="description">
                        {$product.content}
                    </div>
                    <div class="row-center">
                        {$smarty.capture.contact}
                    </div>
                </div>
            </div>
            {if $product.associated}
            <p class="h2">{#similar_products#|ucfirst}</p>
            <div class="vignette-list">
                <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    {include file="catalog/loop/product.tpl" data=$product.associated classCol='vignette col-4 col-xs-3 col-sm-4 col-md-th col-lg-4 col-xl-3'}
                </div>
            </div>
            {/if}
        {/block}
    </article>
{/block}