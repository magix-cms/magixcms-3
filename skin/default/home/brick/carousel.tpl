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
    <amp-img src="/skin/{$theme}/img/carousel/m_urbex.webp"
             alt="Urbex"
             layout="responsive"
             height="384"
             width="960">
        <amp-img src="/skin/{$theme}/img/carousel/m_urbex.jpg"
                 alt="Urbex"
                 layout="responsive"
                 height="384"
                 width="960"
                 fallback>
        </amp-img>
    </amp-img>
    <amp-img src="/skin/{$theme}/img/carousel/m_mountain-walker.webp"
             alt="Mountain Walker"
             layout="responsive"
             height="384"
             width="960">
        <amp-img src="/skin/{$theme}/img/carousel/m_mountain.jpg"
                 alt="Mountain Walker"
                 layout="responsive"
                 height="384"
                 width="960"
                 fallback>
        </amp-img>
    </amp-img>
    <amp-img src="/skin/{$theme}/img/carousel/m_skyscraper.webp"
             alt="Skyscraper"
             layout="responsive"
             height="384"
             width="960">
        <amp-img src="/skin/{$theme}/img/carousel/m_skyscraper.jpg"
                 alt="Skyscraper"
                 layout="responsive"
                 height="384"
                 width="960"
                 fallback>
        </amp-img>
    </amp-img>
</amp-carousel>
{else}
<div id="home-slideshow" class="owl-slideshow">
    <div class="owl-carousel owl-theme">
        <div class="slide" data-dot="<span><span>Slide {$k}</span></span>">
            {strip}<picture>
                <!--[if IE 9]><video style="display: none;"><![endif]-->
                <source type="image/webp" sizes="1920px" media="(min-width: 1200px)" srcset="/skin/{$theme}/img/carousel/l_urbex.webp 1920w">
                <source type="image/webp" sizes="960px" media="(min-width: 768px)" srcset="/skin/{$theme}/img/carousel/m_urbex.webp 960w">
                <source type="image/webp" sizes="480px" srcset="/skin/{$theme}/img/carousel/s_urbex.webp 480w">
                <source type="image/png" sizes="1920px" media="(min-width: 1200px)" srcset="/skin/{$theme}/img/carousel/l_urbex.jpg 1920w">
                <source type="image/png" sizes="960px" media="(min-width: 768px)" srcset="/skin/{$theme}/img/carousel/m_urbex.jpg 960w">
                <source type="image/png" sizes="480px" srcset="/skin/{$theme}/img/carousel/s_urbex.jpg 480w">
                <!--[if IE 9]></video><![endif]-->
                <img src="/skin/{$theme}/img/carousel/s_urbex.jpg" sizes="(min-width: 1200px) 1920px, (min-width: 768px) 960px, 480px" srcset="/skin/{$theme}/img/carousel/l_urbex.jpg 1920w,/skin/{$theme}/img/carousel/m_urbex.jpg 960w,/skin/{$theme}/img/carousel/s_urbex.jpg 480w" alt="Urbex" class="img-responsive lazyload" />
            </picture>{/strip}
        </div>
        <div class="slide" data-dot="<span><span>Slide {$k}</span></span>">
            {strip}<picture>
                <!--[if IE 9]><video style="display: none;"><![endif]-->
                <source type="image/webp" sizes="1920px" media="(min-width: 1200px)" srcset="/skin/{$theme}/img/carousel/l_mountain-walker.webp 1920w">
                <source type="image/webp" sizes="960px" media="(min-width: 768px)" srcset="/skin/{$theme}/img/carousel/m_mountain-walker.webp 960w">
                <source type="image/webp" sizes="480px" srcset="/skin/{$theme}/img/carousel/s_mountain-walker.webp 480w">
                <source type="image/png" sizes="1920px" media="(min-width: 1200px)" srcset="/skin/{$theme}/img/carousel/l_mountain-walker.jpg 1920w">
                <source type="image/png" sizes="960px" media="(min-width: 768px)" srcset="/skin/{$theme}/img/carousel/m_mountain-walker.jpg 960w">
                <source type="image/png" sizes="480px" srcset="/skin/{$theme}/img/carousel/s_mountain-walker.jpg 480w">
                <!--[if IE 9]></video><![endif]-->
                <img src="/skin/{$theme}/img/carousel/s_mountain-walker.jpg" sizes="(min-width: 1200px) 1920px, (min-width: 768px) 960px, 480px" srcset="/skin/{$theme}/img/carousel/l_mountain-walker.jpg 1920w,/skin/{$theme}/img/carousel/m_mountain-walker.jpg 960w,/skin/{$theme}/img/carousel/s_mountain-walker.jpg 480w" alt="Mountain Walker" class="img-responsive lazyload" />
            </picture>{/strip}
        </div>
        <div class="slide" data-dot="<span><span>Slide {$k}</span></span>">
            {strip}<picture>
                <!--[if IE 9]><video style="display: none;"><![endif]-->
                <source type="image/webp" sizes="1920px" media="(min-width: 1200px)" srcset="/skin/{$theme}/img/carousel/l_skyscraper.webp 1920w">
                <source type="image/webp" sizes="960px" media="(min-width: 768px)" srcset="/skin/{$theme}/img/carousel/m_skyscraper.webp 960w">
                <source type="image/webp" sizes="480px" srcset="/skin/{$theme}/img/carousel/s_skyscraper.webp 480w">
                <source type="image/png" sizes="1920px" media="(min-width: 1200px)" srcset="/skin/{$theme}/img/carousel/l_skyscraper.jpg 1920w">
                <source type="image/png" sizes="960px" media="(min-width: 768px)" srcset="/skin/{$theme}/img/carousel/m_skyscraper.jpg 960w">
                <source type="image/png" sizes="480px" srcset="/skin/{$theme}/img/carousel/s_skyscraper.jpg 480w">
                <!--[if IE 9]></video><![endif]-->
                <img src="/skin/{$theme}/img/carousel/s_skyscraper.jpg" sizes="(min-width: 1200px) 1920px, (min-width: 768px) 960px, 480px" srcset="/skin/{$theme}/img/carousel/l_skyscraper.jpg 1920w,/skin/{$theme}/img/carousel/m_skyscraper.jpg 960w,/skin/{$theme}/img/carousel/s_skyscraper.jpg 480w" alt="Skyscraper" class="img-responsive lazyload" />
            </picture>{/strip}
        </div>
    </div>
    <div class="owl-slideshow-nav">
        <div class="owl-slideshow-dots"></div>
    </div>
</div>
{/if}