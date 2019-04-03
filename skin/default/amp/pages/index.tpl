{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/pages.min.css"}{/block}
{block name="title"}{$pages.seo.title}{/block}
{block name="description"}{$pages.seo.description}{/block}
{block name="amp-script"}
    {if $pages.img.large.src}
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        {amp_components content=$pages.content image=false}
    {else}
        {amp_components content=$pages.content}
    {/if}
{/block}
{block name='article:content'}
    <h1 itemprop="name">{$pages.name}</h1>
    <div class="text" itemprop="text">
        {if !empty($pages.img.large.src)}
            <figure>
                <amp-img on="tap:lightbox1"
                         role="button"
                         tabindex="0"
                         alt="{$pages.img.alt}"
                         title="{$pages.img.title}"
                         src="{$pages.img.medium['src_webp']}"
                         width="{$pages.img.medium['w']}"
                         height="{$pages.img.medium['h']}"
                         layout="responsive">
                    <amp-img on="tap:lightbox1"
                             role="button"
                             tabindex="0"
                             alt="{$pages.img.alt}"
                             fallback
                             title="{$pages.img.title}"
                             src="{$pages.img.medium['src']}"
                             width="{$pages.img.medium['w']}"
                             height="{$pages.img.medium['h']}"
                             layout="responsive">

                    </amp-img>
                </amp-img>
                <figcaption class="hidden">{$pages.img.caption}</figcaption>
            </figure>
            <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
        {/if}
        {amp_content content=$pages.content}
    </div>
{/block}