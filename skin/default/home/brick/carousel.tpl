{if !isset($variant)}
    {$variant = 'default'}
{/if}
{if !isset($lazy)}
    {$lazy = true}
{/if}
{if $amp}
<amp-carousel id="home-slideshow" class="carousel2"
              type="slides"
              autoplay
              loop
              controls
              delay="3000"
              layout="responsive"
              height="384"
              width="960">
    <amp-img data-src="/skin/{$theme}/img/carousel/{$variant}/m_slide-1.webp"
             alt="Slide 1"
             layout="responsive"
             height="384"
             width="960">
        <amp-img data-src="/skin/{$theme}/img/carousel/{$variant}/m_slide-1.jpg"
                 alt="Slide 1"
                 layout="responsive"
                 height="384"
                 width="960"
                 fallback>
        </amp-img>
    </amp-img>
    <amp-img data-src="/skin/{$theme}/img/carousel/{$variant}/m_slide-2.webp"
             alt="Slide 2"
             layout="responsive"
             height="384"
             width="960">
        <amp-img data-src="/skin/{$theme}/img/carousel/{$variant}/m_mountain.jpg"
                 alt="Slide 2"
                 layout="responsive"
                 height="384"
                 width="960"
                 fallback>
        </amp-img>
    </amp-img>
    <amp-img data-src="/skin/{$theme}/img/carousel/{$variant}/m_slide-3.webp"
             alt="Slide 3"
             layout="responsive"
             height="384"
             width="960">
        <amp-img data-src="/skin/{$theme}/img/carousel/{$variant}/m_slide-3.jpg"
                 alt="Slide 3"
                 layout="responsive"
                 height="384"
                 width="960"
                 fallback>
        </amp-img>
    </amp-img>
</amp-carousel>
{else}
<div id="home-slideshow">
    <div class="slideshow">
        {for $k=1 to 3}
        <div class="slide" data-dot="<span><span>Slide {$k}</span></span>">
            {strip}<picture>
                <!--[if IE 9]><video style="display: none;"><![endif]-->
                <source type="image/webp" sizes="1920px" media="(min-width: 960px)" srcset="/skin/{$theme}/img/carousel/{$variant}/l_slide-{$k}.webp 1920w">
                <source type="image/webp" sizes="960px" media="(min-width: 480px)" srcset="/skin/{$theme}/img/carousel/{$variant}/m_slide-{$k}.webp 960w">
                <source type="image/webp" sizes="480px" srcset="/skin/{$theme}/img/carousel/{$variant}/s_slide-{$k}.webp 480w">
                <source type="image/png" sizes="1920px" media="(min-width: 960px)" srcset="/skin/{$theme}/img/carousel/{$variant}/l_slide-{$k}.jpg 1920w">
                <source type="image/png" sizes="960px" media="(min-width: 480px)" srcset="/skin/{$theme}/img/carousel/{$variant}/m_slide-{$k}.jpg 960w">
                <source type="image/png" sizes="480px" srcset="/skin/{$theme}/img/carousel/{$variant}/s_slide-{$k}.jpg 480w">
                <!--[if IE 9]></video><![endif]-->
                <img src="/skin/{$theme}/img/carousel/{$variant}/s_slide-{$k}.jpg"
                     sizes="(min-width: 960px) 1920px, (min-width: 480px) 960px, 480px"
                     srcset="/skin/{$theme}/img/carousel/{$variant}/l_slide-{$k}.jpg 1920w,/skin/{$theme}/img/carousel/{$variant}/m_slide-{$k}.jpg 960w,/skin/{$theme}/img/carousel/{$variant}/s_slide-{$k}.jpg 480w"
                     alt="Slide {$k}"
                     class="img-responsive{if $lazy} lazyload{/if}"{if $lazy} loading="lazy"{/if}
                     width="1920" height="768"/>
            </picture>{/strip}
        </div>
        {/for}
    </div>
</div>
{/if}