{extends file="amp/layout.tpl"}
{block name="amp-script" append}
    <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
    <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
{/block}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/pages.min.css"}{/block}
{block name='body:id'}pages{/block}
{block name='article:content'}
    <div class="container">
        <h1 itemprop="name">{$pages.title}</h1>
    </div>
    {capture name="pageImg"}
    {if !empty($pages.imgSrc)}
    <figure>
        <amp-img on="tap:lightbox1"
                 role="button"
                 tabindex="0"
                 src="{$pages.imgSrc.large}"
                 alt="{$pages.title}"
                 title="{$pages.title}"
                 layout="responsive"
                 width="960"
                 height="720"></amp-img>
        <figcaption class="hidden">{$pages.title}</figcaption>
    </figure>
    <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
    {/if}
    {/capture}
    <div itemprop="text">
        {amp_img content={$pages.content|replace:'[[IMG]]':$smarty.capture.pageImg}}
    </div>
    <div class="container">
        <figure>
            <amp-img on="tap:my-lightbox"
                     role="button"
                     tabindex="0"
                     src="/skin/{template}/img/carousel/carousel-urbex.jpg"
                     alt="{$pages.title}"
                     title="{$pages.title}"
                     layout="responsive"
                     width="1920"
                     height="500"></amp-img>
            <figcaption class="hidden">{$pages.title}</figcaption>
        </figure>
        <figure>
            <amp-img on="tap:my-lightbox"
                     role="button"
                     tabindex="0"
                     src="/skin/{template}/img/carousel/carousel-skyscraper.jpg"
                     alt="{$pages.title}"
                     title="{$pages.title}"
                     layout="responsive"
                     width="1920"
                     height="500"></amp-img>
            <figcaption class="hidden">{$pages.title}</figcaption>
        </figure>
        <figure>
            <amp-img on="tap:my-lightbox"
                     role="button"
                     tabindex="0"
                     src="/skin/{template}/img/carousel/carousel-mountain-walker.jpg"
                     alt="{$pages.title}"
                     title="{$pages.title}"
                     layout="responsive"
                     width="1920"
                     height="500"></amp-img>
            <figcaption class="hidden">{$pages.title}</figcaption>
        </figure>
    </div>
    <amp-lightbox id="my-lightbox"
                  layout="nodisplay">
            <amp-carousel class="collapsible-captions"
                          height="332"
                          layout="fixed-height"
                          type="slides">
                <figure>
                    <div class="fixed-height-container">
                        <amp-img src="/skin/{template}/img/carousel/carousel-urbex.jpg"
                                 layout="fill"
                                 on="tap:my-lightbox.close"
                                 role="button"
                                 tabindex="0"></amp-img>
                    </div>
                    <figcaption></figcaption>
                </figure>
                <figure>
                    <div class="fixed-height-container">
                        <amp-img src="/skin/{template}/img/carousel/carousel-skyscraper.jpg"
                                 layout="fill"
                                 on="tap:my-lightbox.close"
                                 role="button"
                                 tabindex="1"></amp-img>
                    </div>
                    <figcaption></figcaption>
                </figure>
                <figure>
                    <div class="fixed-height-container">
                        <amp-img src="/skin/{template}/img/carousel/carousel-mountain-walker.jpg"
                                 layout="fill"
                                 on="tap:my-lightbox.close"
                                 role="button"
                                 tabindex="2"></amp-img>
                    </div>
                    <figcaption></figcaption>
                </figure>
            </amp-carousel>
        <div class="lightbox"
             on="tap:my-lightbox.close"
             role="button"
             tabindex="0"></div>
    </amp-lightbox>

    <figure on="tap:my-lightbox-2"
    role="button"
    tabindex="0">
        <amp-img on="tap:lightbox2"
                 role="button"
                 tabindex="0"
                 src="/skin/{template}/img/carousel/carousel-urbex.jpg"
                 alt="{$pages.title}"
                 title="{$pages.title}"
                 layout="responsive"
                 width="1920"
                 height="500"></amp-img>
        <figcaption class="hidden">{$pages.title}</figcaption>
    </figure>

    <amp-lightbox id="my-lightbox-2"
                  layout="nodisplay">
            <amp-image-lightbox id="lightbox2" layout="nodisplay"></amp-image-lightbox>
    </amp-lightbox>

    {*<h3>Données hreflang</h3>
    <pre>{$hreflang|print_r}</pre>*}
    {*<h3>Données du widget</h3>
    <h2>{#ma_variable#}</h2>
    {widget_cms_data
    conf = [
    'context' => 'all'
    ]
    assign="pages"
    }
    *}{*'select' => ["fr" => "31"], 'exclude' => ["fr" => "31,16"] *}{*
    <h2>Les pages</h2>
    <pre>
    {$pages|print_r}*}
</pre>
{/block}