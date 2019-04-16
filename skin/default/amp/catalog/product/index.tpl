{extends file="amp/catalog/index.tpl"}
{if !empty($product.long_name)}{$product.name = $product.long_name}{/if}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/product.min.css"}{/block}
{block name="amp-script"}
    {if count($product.imgs) > 1}
        <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
        <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
        {amp_components content=$product.content galery=false}
    {elseif count($product.imgs) > 0}
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        {amp_components content=$product.content image=false}
    {/if}
{/block}
{block name="webType"}ItemPage{/block}
{block name='body:id'}product{/block}
{block name="title"}{$product.seo.title}{/block}
{block name="description"}{$product.seo.description}{/block}
{block name='article'}
    {capture name="contact"}
        <p class="text-center interested-form">
            <a href="{$url}/{$lang}/amp/contact/?moreinfo={$product.name}" class="btn btn-box btn-invert btn-main-theme">{#interested_in#} {$product.name}&thinsp;?</a>
        </p>
    {/capture}
    <article class="catalog container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Product">
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
            {if count($product.imgs) > 1}
            <div id="gallery">
                <amp-carousel id="carousel-with-preview"
                              width="{$product.imgs[0].img.medium['w']}"
                              height="{$product.imgs[0].img.medium['h']}"
                              layout="responsive"
                              type="slides" controls loop>
                    {foreach $product.imgs as $img}
                    <button on="tap:lightbox-gallery,carousel.goToSlide(index={$img@index})">
                        <i class="material-icons">fullscreen</i>
                        <amp-img src="{$img.img.medium['src_webp']}"
                                 width="{$img.img.medium['w']}"
                                 height="{$img.img.medium['h']}"
                                 layout="responsive"
                                 alt="{$img.img.alt}"
                                 title="{$img.img.title}">
                            <amp-img alt="{$img.img.alt}"
                                     fallback
                                     title="{$img.img.title}"
                                     src="{$img.img.medium['src']}"
                                     width="{$img.img.medium['w']}"
                                     height="{$img.img.medium['h']}"
                                     layout="responsive">
                            </amp-img>
                        </amp-img>
                    </button>
                    {/foreach}
                </amp-carousel>
                <amp-carousel class="carousel-preview"
                              width="auto"
                              height="78"
                              layout="fixed-height"
                              type="carousel" controls>
                    {foreach $product.imgs as $img}
                    <button on="tap:carousel-with-preview.goToSlide(index={$img@index})">
                        {*<amp-img src="{$url}{$img.img.small.src}"
                                 width="125"
                                 height="78"
                                 layout="fixed"
                                 alt="{$product.name}"></amp-img>*}
                        <amp-img src="{$img.img.small['src_webp']}"
                                 width="125"
                                 height="78"
                                 layout="fixed"
                                 alt="{$img.img.alt}"
                                 title="{$img.img.title}">
                            <amp-img alt="{$img.img.alt}"
                                     fallback
                                     title="{$img.img.title}"
                                     src="{$img.img.small['src']}"
                                     width="125"
                                     height="78"
                                     layout="fixed">
                            </amp-img>
                        </amp-img>
                    </button>
                    {/foreach}
                </amp-carousel>
                <amp-lightbox id="lightbox-gallery" layout="nodisplay">
                    <div class="lightbox">
                        <amp-carousel id="carousel" layout="fill" type="slides">
                            {foreach $product.imgs as $img}
                            <figure class="amp-carousel-slide">
                                {*<amp-img on="tap:lightbox-gallery.close"
                                         role="button"
                                         tabindex="0"
                                         src="{$url}{$img.img.large.src}"
                                         layout="responsive"
                                         height="618"
                                         width="1000"
                                         itemprop="image"></amp-img>*}
                                <amp-img on="tap:lightbox-gallery.close"
                                         role="button"
                                         tabindex="0"
                                         src="{$img.img.large['src_webp']}"
                                         width="{$img.img.large['w']}"
                                         height="{$img.img.large['h']}"
                                         layout="responsive"
                                         alt="{$img.img.alt}"
                                         title="{$img.img.title}"
                                         itemprop="image">
                                    <amp-img on="tap:lightbox-gallery.close"
                                             role="button"
                                             tabindex="0"
                                             alt="{$img.img.alt}"
                                             fallback
                                             title="{$img.img.title}"
                                             src="{$img.img.large['src']}"
                                             width="{$img.img.large['w']}"
                                             height="{$img.img.large['h']}"
                                             layout="responsive">
                                    </amp-img>
                                </amp-img>
                                <figcaption>{$product.name}</figcaption>
                            </figure>
                            {/foreach}
                        </amp-carousel>
                        <div class="close-btn" on="tap:lightbox-gallery.close" role="button" tabindex="0"><i class="material-icons">close</i></div>
                    </div>
                </amp-lightbox>
            </div>
            {elseif count($product.imgs) > 0}
            {$img = $product.imgs[0]}
            <figure>
                {*<amp-img on="tap:lightbox1"
                         role="button"
                         tabindex="0"
                         src="{$img.img.large.src}"
                         alt="{$product.name}"
                         title="{$product.name}"
                         layout="responsive"
                         width="1000"
                         height="618"
                         itemprop="image"></amp-img>*}
                <amp-img on="tap:lightbox1"
                         role="button"
                         tabindex="0"
                         src="{$img.img.large['src_webp']}"
                         width="{$img.img.large['w']}"
                         height="{$img.img.large['h']}"
                         layout="responsive"
                         alt="{$img.img.alt}"
                         title="{$img.img.title}"
                         itemprop="image">
                    <amp-img on="tap:lightbox1"
                             role="button"
                             tabindex="0"
                             alt="{$img.img.alt}"
                             fallback
                             title="{$img.img.title}"
                             src="{$img.img.large['src']}"
                             width="{$img.img.large['w']}"
                             height="{$img.img.large['h']}"
                             layout="responsive">
                    </amp-img>
                </amp-img>
                <figcaption class="hidden">{$product.name}</figcaption>
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
                        <div class="row row-center" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                            {include file="amp/catalog/loop/product.tpl" data=$product.associated classCol='vignette col-12 col-xs-6 col-md-4'}
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}