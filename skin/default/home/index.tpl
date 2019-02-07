{extends file="layout.tpl"}
{block name="title"}{$home.seo.title}{/block}
{block name="description"}{$home.seo.description}{/block}
{block name='body:id'}home{/block}

{block name="main:before"}
    {include file="home/brick/carousel.tpl"}
{/block}

{block name='article:content'}
    <h1 itemprop="name">{$home.name}</h1>
    <div itemprop="text">
        {$home.content}
    </div>
{/block}

{block name="main:after"}
    {include file="news/brick/last-news.tpl"}
{/block}