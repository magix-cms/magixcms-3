{extends file="layout.tpl"}
{block name='body:id'}pages{/block}
{block name="title"}{$pages.seoTitle}{/block}
{block name="description"}{$pages.seoDescr}{/block}
{block name='article:content'}
    <h1 itemprop="name">{$pages.title}</h1>
    <div class="text" itemprop="text">
        {if !empty($pages.imgSrc.medium)}
            <figure>
                <a href="{$pages.imgSrc.large}" class="img-zoom" title="{$pages.title}">
                    <img class="img-responsive" src="{$pages.imgSrc.medium}" alt="{$pages.title}" title="{$pages.title}" />
                </a>
            </figure>
        {/if}
        {$pages.content}
    </div>
{/block}