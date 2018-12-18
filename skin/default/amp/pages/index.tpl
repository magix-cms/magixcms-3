{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/pages.min.css"}{/block}
{block name='body:id'}pages{/block}
{block name="title"}{if $pages.seoTitle}{$pages.seoTitle}{else}{$pages.title}{/if}{/block}
{block name="description"}{if $pages.seoTitle}{$pages.seoDescr}{elseif !empty($pages.resume)}{$pages.resume}{elseif !empty($pages.content)}{$pages.content|strip_tags|truncate:100:'...'}{/if}{/block}
{block name="amp-script"}
    {if $pages.img.large.src}
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        {amp_components content=$pages.content image=false}
    {else}
        {amp_components content=$pages.content}
    {/if}
{/block}
{block name='article:content'}
    <h1 itemprop="name">{$pages.title}</h1>
    <div class="text" itemprop="text">
        {if !empty($pages.img.large.src)}
            <figure>
                <amp-img on="tap:lightbox1"
                         role="button"
                         tabindex="0"
                         src="{$pages.img.large.src}"
                         alt="{$pages.title}"
                         title="{$pages.title}"
                         layout="responsive"
                         width="{$pages.img.large['w']}"
                         height="{$pages.img.large['h']}"></amp-img>
                <figcaption class="hidden">{$pages.title}</figcaption>
            </figure>
            <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
        {/if}
        {amp_content content=$pages.content}
    </div>
{/block}