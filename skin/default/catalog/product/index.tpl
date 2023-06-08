{extends file="catalog/index.tpl"}
{if !empty($product.long_name)}{$product.name = $product.long_name}{/if}
{block name="title" nocache}{$product.seo.title}{/block}
{block name="description" nocache}{$product.seo.description}{/block}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name="styleSheet" nocache}
    {$css_files = ["product","gallery","lightbox","slider"]}
{/block}

{block name='article'}
    {capture name="contact" nocache}
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
        {block name='article:content' nocache}
            {if $product.long_name !== ''}<meta itemprop="name" content="{$product.short_name}">{/if}
            <header>
                <h1 itemprop="{if $product.long_name !== ''}alternateName{else}name{/if}">{$product.name}</h1>
                {if $product.reference}<span class="ref">{#product_ref#}&nbsp;{$product.reference}</span>{/if}
                {if $product.price !== '0.00' && $setting.price_display === 'tinc'}
                    {$price = $product.price * (1 + ($setting.vat_rate/100))}
                {else}
                    {$price = $product.price}
                {/if}
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price" content="{$price|round:2|number_format:2:',':' '|decimal_trim:','}"></span><span itemprop="priceCurrency" content="EUR"></span>
                </span>
            </header>
            <div itemprop="category" itemscope itemtype="http://schema.org/Series">
                <meta itemprop="name" content="{$parent.name}">
                <meta itemprop="url" content="{$parent.url}">
            </div>
            <div class="row row-center">
                {if is_array($product.imgs) && count($product.imgs) > 0}
                <div class="col-12 col-md-6 col-lg-5 col-xl-4">
                    {include file="img/loop/gallery.tpl" imgs=$product.imgs}
                </div>
                {/if}
                <div class="col-12{if is_array($product.imgs) && count($product.imgs) > 0} col-md-6 col-lg-7 col-xl-8{/if}">
                    {strip}{if $product.price !== '0.00'}<div class="price">{$price|round:2|number_format:2:',':' '|decimal_trim:','}&nbsp;€&nbsp;{if $setting.price_display === 'tinc'}{#tax_included#}{else}{#tax_excluded#}{/if}</div>{/if}{/strip}
                    <div class="text" itemprop="description">
                        {$product.content}
                    </div>
                    <div class="row-center">
                        {$smarty.capture.contact}
                    </div>
                </div>
            </div>
            {if $associated}
            <p class="h2">{#similar_products#|ucfirst}</p>
            <div class="list-grid product-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                {include file="catalog/loop/product.tpl" data=$associated classCol='vignette' nocache}
            </div>
            {/if}
        {/block}
    </article>
{/block}