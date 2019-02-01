<div id="gallery">
    <div class="image-gallery">
        <div class="big-image">
            {foreach $product.imgs as $k => $item}
                <a id="{if $item.default}default{else}img{$k}{/if}" class="img-gallery" href="{$item.img.large.src}" rel="productGallery" title="{$item.title}" data-caption="{$item.caption}" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                    <meta itemprop="contentUrl" content="{$item.img.medium.src}" />
                    <span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                        <img itemprop="image" class="img-responsive" src="{$item.img.medium.src}" alt="{$item.alt}" itemprop="contentUrl"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>
                    </span>
                </a>
            {/foreach}
            {*{if $product.price != 0}
            <p class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span itemprop="price">{$product.price}</span> <span itemprop="priceCurrency" content="EUR">€</span> TTC
            </p>
            {/if}*}
        </div>

        {*<div class="thumbs three-thumbs smooth-gallery">
            {if {$product.imgs|count} > 3}
                <a class="button prev"><span class="fa fa-angle-left"></span></a>
                <a class="button next"><span class="fa fa-angle-right"></span></a>
            {/if}
            {strip}<ul class="list-unstyled">
                {foreach $product.imgs as $k => $item}
                <li class="item{if $item@index < 3} active{/if}" aria-hidden="false"><a class="show-img" href="#" data-target="#{if $item.default}default{else}img{$k}{/if}" rel="productGallery"><img src="{$product.img_default}" class="img-responsive lazy" data-src="{$item.img.small.src}" alt="{$product.name|ucfirst}"{if $item.img.small.crop === 'adaptative'} width="{$item.img.small.w}" height="{$item.img.small.h}"{/if}/></a></li>
                {/foreach}
            </ul>{/strip}
        </div>*}

        <div class="owl-carousel owl-theme-square thumbs">
            {foreach $product.imgs as $k => $item}
                <a class="show-img" href="#" data-target="#{if $item.default}default{else}img{$k}{/if}" rel="productGallery"><img {*src="{$product.img_default}"*} class="img-responsive owl-lazy" data-src="{$item.img.small.src}" alt="{$item.alt}"{if $item.img.small.crop === 'adaptative'} width="{$item.img.small.w}" height="{$item.img.small.h}"{/if}/></a>
            {/foreach}
        </div>
    </div>
</div>