{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/pages.min.css"}{/block}
{block name='body:id'}pages{/block}
{block name="title"}{$pages.seoTitle}{/block}
{block name="description"}{$pages.seoDescr}{/block}
{block name='article:content'}
    <h1 itemprop="name">{$pages.title}</h1>
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
        {amp_content content={$pages.content|replace:'[[IMG]]':$smarty.capture.pageImg}}
    </div>
{/block}
{block name="amp-script" append}
    {amp_components content={$pages.content|replace:'[[IMG]]':$smarty.capture.pageImg}}
{/block}