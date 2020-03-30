{if isset($product.imgs) && is_array($product.imgs) && count($product.imgs) > 0}
<div id="gallery">
    <div class="image-gallery">
        <div class="big-image">
        {foreach $product.imgs as $k => $item}
            <a id="{if $item.default}default{else}img{$k}{/if}" class="img-gallery" href="{$item.img.large.src}" title="{$item.img.title}" data-caption="{$item.img.caption}" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                <meta itemprop="contentUrl" content="{$item.img.small.src}" />
                <span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                    {include file="img/img.tpl" img=$item.img lazy=true size='medium'}
                </span>
            </a>
        {/foreach}
        </div>
        {if count($product.imgs) > 1}
            <div class="owl-carousel owl-theme-square thumbs">
                {foreach $product.imgs as $k => $item}
                    <a class="show-img" href="#" data-target="#{if $item.default}default{else}img{$k}{/if}">
                        {include file="img/img.tpl" img=$item.img size='small' lazy=true lazyClass='lazyload'}
                    </a>
                {/foreach}
            </div>
        {/if}
    </div>
</div>
{/if}