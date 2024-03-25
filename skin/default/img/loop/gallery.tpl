{if isset($imgs) && is_array($imgs) && count($imgs) > 0}
<div id="gallery">
    <div class="image-gallery">
        <div class="big-image">
        {foreach $imgs as $k => $img}
            <a id="{if $img.default}default{else}img{$k}{/if}" class="img-gallery" href="{$img.large.src}" title="{$img.title}" data-caption="{$img.caption}" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                <meta itemprop="contentUrl" content="{$img.small.src}" />
                <span itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                    {include file="img/img.tpl" img=$img lazy=true size='medium' lazyClass='lazyload'}
                </span>
                {if isset($badge) && $badge != NULL}{$badge}{/if}
            </a>
        {/foreach}
        </div>
        {if count($imgs) > 1}
            <div class="owl-carousel owl-theme-square thumbs">
                {foreach $imgs as $k => $img}
                    <a class="show-img" href="#" data-target="#{if $img.default}default{else}img{$k}{/if}">
                        {include file="img/img.tpl" img=$img size='small' lazy=true lazyClass='lazyload'}
                    </a>
                {/foreach}
            </div>
        {/if}
    </div>
</div>
{/if}