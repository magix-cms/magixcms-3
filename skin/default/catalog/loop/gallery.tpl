<div id="gallery">
    <div class="image-gallery">
        <div class="big-image">
            {foreach $product.imgs as $k => $item}
                <a id="{if $item.default}default{else}img{$k}{/if}" class="img-gallery" href="{$item.img.large.src}"{* rel="productGallery"*} title="{$item.img.title}" data-caption="{$item.img.caption}" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                    <meta itemprop="contentUrl" content="{$item.img.medium.src}" />
                    <span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                        {*<img itemprop="image" class="img-responsive" src="{$item.img.medium.src}" alt="{$item.img.alt}" itemprop="contentUrl"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>*}
                       {strip}<picture>
                           {if isset($item.img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
                           <source type="image/webp" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src_webp']} {$item.img.medium['w']}w">
                            <source type="{$item.img.medium.ext}" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src']} {$item.img.medium['w']}w">
                           <!--[if IE 9]></video><![endif]-->{/if}
                            <img src="{$item.img.medium.src}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium['w']}" height="{$item.img.medium['h']}"{/if} alt="{$item.img.alt}" title="{$item.img.title}" class="img-responsive" />
                           </picture>{/strip}
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
                {*<a class="show-img" href="#" data-target="#{if $item.default}default{else}img{$k}{/if}" rel="productGallery"><img class="img-responsive owl-lazy" data-src="{$item.img.small.src}" alt="{$item.img.alt}"{if $item.img.small.crop === 'adaptative'} width="{$item.img.small.w}" height="{$item.img.small.h}"{/if}/></a>*}
            <a class="show-img" href="#" data-target="#{if $item.default}default{else}img{$k}{/if}" {*rel="productGallery"*}>
                {strip}<picture>
                    {if isset($item.img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source type="image/webp" sizes="{$item.img.small['w']}px" srcset="{$item.img.small['src_webp']} {$item.img.small['w']}w">
                    <source type="{$item.img.small.ext}" sizes="{$item.img.small['w']}px" srcset="{$item.img.small['src']} {$item.img.small['w']}w">
                    <!--[if IE 9]></video><![endif]-->{/if}
                    <img data-src="{$item.img.small.src}" itemprop="image"{if $item.img.small.crop === 'adaptative'} width="{$item.img.small['w']}" height="{$item.img.small['h']}"{/if} alt="{$item.img.alt}" title="{$item.img.title}" class="img-responsive owl-lazy" />
                </picture>{/strip}
            </a>
            {/foreach}
        </div>
    </div>
</div>