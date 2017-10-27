{extends file="layout.tpl"}
{*{block name="title"}{static_metas param=$smarty.config.website_name dynamic=$home.seoTitle}{/block}*}
{*{block name="description"}{static_metas param=$smarty.config.website_name dynamic=$home.seoDescr}{/block}*}
{block name='body:id'}home{/block}

{block name='article'}
    <article class="container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            {if $viewport !== 'mobile'}<h1 itemprop="name">{$home.name}</h1>{/if}
            <div itemprop="text">
                {$home.content}
            </div>
        {/block}
    </article>
{/block}

{block name="main:after"}
    {include file="home/brick/main-cat.tpl"}
    {include file="news/brick/last-news.tpl"}
{/block}