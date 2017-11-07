{extends file="layout.tpl"}
{*{block name="title"}{static_metas param=$smarty.config.website_name dynamic=$home.seoTitle}{/block}*}
{*{block name="description"}{static_metas param=$smarty.config.website_name dynamic=$home.seoDescr}{/block}*}
{block name='body:id'}home{/block}

{block name='article:content'}
    <h1 itemprop="name">{$home.name}</h1>
    <div itemprop="text">
        {$home.content}
    </div>
{/block}

{block name="main:after"}
    {include file="home/brick/main-cat.tpl"}
    {include file="news/brick/last-news.tpl"}
{/block}