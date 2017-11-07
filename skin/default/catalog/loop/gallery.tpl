<div id="gallery">
    <div class="image-gallery">
        <div class="big-image">
            {foreach $product.img as $k => $item}
                <a id="{if $item.default}default{else}img{$k}{/if}" class="img-gallery" href="{$item.imgSrc.large}" rel="productGallery" title="{$product.name}" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                    <meta itemprop="contentUrl" content="{$item.imgSrc.medium}" />
                    <span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                        <img itemprop="image" class="img-responsive"  src="{$item.imgSrc.small}" alt="{$product.name|ucfirst}" itemprop="contentUrl"/>
                    </span>
                </a>
            {/foreach}
            {*{if $product.price != 0}
            <p class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span itemprop="price">{$product.price}</span> <span itemprop="priceCurrency" content="EUR">€</span> TTC
            </p>
            {/if}*}
        </div>

        <div class="thumbs three-thumbs smooth-gallery">
            {if {$product.img|count} > 3}
                <a class="button prev"><span class="fa fa-angle-left"></span></a>
                <a class="button next"><span class="fa fa-angle-right"></span></a>
            {/if}
            {strip}<ul class="list-unstyled">
                {foreach $product.img as $k => $item}
                <li class="item{if $item@index < 3} active{/if}" aria-hidden="false"><a class="show-img" href="#" data-target="#{if $item.default}default{else}img{$k}{/if}" rel="productGallery"><img class="img-responsive" src="{$item.imgSrc.small}" alt="{$product.name|ucfirst}"/></a></li>
                {/foreach}
            </ul>{/strip}
        </div>
    </div>
</div>