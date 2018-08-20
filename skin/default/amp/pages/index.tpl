{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/pages.min.css"}{/block}
{block name='body:id'}pages{/block}
{block name="title"}{if $pages.seoTitle}{$pages.seoTitle}{else}{$pages.title}{/if}{/block}
{block name="description"}{if $pages.seoTitle}{$pages.seoDescr}{elseif !empty($pages.resume)}{$pages.resume}{elseif !empty($pages.content)}{$pages.content|truncate:100:'...'}{/if}{/block}
{block name="amp-script"}
    {if $pages.imgSrc.large}
        <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
        {amp_components content=$pages.content image=false}
    {else}
        {amp_components content=$pages.content}
    {/if}
{/block}
{block name='article:content'}
    <h1 itemprop="name">{$pages.title}</h1>
    <div class="text" itemprop="text">
        {if !empty($pages.imgSrc)}
            <figure>
                <amp-img on="tap:lightbox1"
                         role="button"
                         tabindex="0"
                         src="{$pages.imgSrc.large}"
                         alt="{$pages.title}"
                         title="{$pages.title}"
                         layout="responsive"
                         width="1000"
                         height="618"></amp-img>
                <figcaption class="hidden">{$pages.title}</figcaption>
            </figure>
            <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
        {/if}
        {amp_content content=$pages.content}
    </div>
{/block}