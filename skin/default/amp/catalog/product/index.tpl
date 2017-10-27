{extends file="amp/catalog/index.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/product.min.css"}{/block}
{block name="amp-script"}
    {if count($product.img) > 1}
        <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
        <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
        {amp_components content=$product.content galery=false}
    {elseif count($product.img) > 0}
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        {amp_components content=$product.content image=false}
    {/if}
{/block}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name='article'}
    {capture name="contact"}
        {*<form action="/{getlang}/contact/" method="get" class="interested-form">
            <fieldset class="text-center">
                *}{*<p class="lead">{#interested_in#} {$product.name}&thinsp;?</p>*}{*
                <p>
                    <input type="hidden" name="moreinfo" value="{$product.name}"/>
                    *}{*<button id="more-info" type="submit" class="btn btn-box btn-invert btn-main-theme">{#contact_form#|ucfirst}</button>*}{*
                    <button id="more-info" type="submit" class="btn btn-box btn-invert btn-main-theme">{#interested_in#} {$product.name}&thinsp;?</button>
                </p>
            </fieldset>
        </form>*}
        <p class="text-center interested-form">
            <a href="{geturl}/{getlang}/amp/contact/?moreinfo={$product.name}" class="btn btn-box btn-invert btn-main-theme">{#interested_in#} {$product.name}&thinsp;?</a>
        </p>
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
            {if count($product.img) > 1}
            <div id="gallery">
                <amp-carousel id="carousel-with-preview"
                              width="1000"
                              height="618"
                              layout="responsive"
                              type="slides">
                    {foreach $product.img as $img}
                    <button on="tap:lightbox-gallery,carousel.goToSlide(index={$img@index})">
                        <i class="material-icons">fullscreen</i>
                        <amp-img src="{geturl}{$img.imgSrc.large}"
                                 width="1000"
                                 height="618"
                                 layout="responsive"
                                 alt="{$product.name}"></amp-img>
                    </button>
                    {/foreach}
                </amp-carousel>
                <amp-carousel class="carousel-preview"
                              width="auto"
                              height="78"
                              layout="fixed-height"
                              type="carousel">
                    {foreach $product.img as $img}
                    <button on="tap:carousel-with-preview.goToSlide(index={$img@index})">
                        <amp-img src="{geturl}{$img.imgSrc.small}"
                                 width="125"
                                 height="78"
                                 layout="fixed"
                                 alt="{$product.name}"></amp-img>
                    </button>
                    {/foreach}
                </amp-carousel>
                <amp-lightbox id="lightbox-gallery" layout="nodisplay">
                    <div class="lightbox">
                        <amp-carousel id="carousel" layout="fill" type="slides">
                            {foreach $product.img as $img}
                            <figure class="amp-carousel-slide">
                                <amp-img on="tap:lightbox-gallery.close"
                                         role="button"
                                         tabindex="0"
                                         src="{geturl}{$img.imgSrc.large}"
                                         layout="responsive"
                                         height="618"
                                         width="1000"
                                         itemprop="image"></amp-img>
                                <figcaption>{$product.name}</figcaption>
                            </figure>
                            {/foreach}
                        </amp-carousel>
                        <div class="close-btn" on="tap:lightbox-gallery.close" role="button" tabindex="0"><i class="material-icons">close</i></div>
                    </div>
                </amp-lightbox>
            </div>
            {elseif count($product.img) > 0}
            {$img = $product.img[0]}
            <figure>
                <amp-img on="tap:lightbox1"
                         role="button"
                         tabindex="0"
                         src="{$img.imgSrc.large}"
                         alt="{$product.name}"
                         title="{$product.name}"
                         layout="responsive"
                         width="1000"
                         height="618"
                         itemprop="image"></amp-img>
                <figcaption class="hidden">{$pages.name}</figcaption>
            </figure>
            <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
            {/if}
            <div itemprop="description">
                {amp_content content=$product.content}
            </div>
            <div class="row-center">
                {$smarty.capture.contact}
            </div>
            {if $product.associated}
                <h3>{#similar_products#|ucfirst}</h3>
                <div class="vignette-list">
                    <div class="section-block">
                        <div class="row row-center">
                            {include file="amp/catalog/loop/product.tpl" data=$product.associated classCol='vignette col-ph-12 col-xs-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}