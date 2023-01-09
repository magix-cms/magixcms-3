{extends file="layout.tpl"}
{block name="title"}{$home.seo.title}{/block}
{block name="description"}{$home.seo.description}{/block}
{block name='body:id'}home{/block}
{block name="styleSheet"}
    {$css_files = ["home","lightbox","slider"]}
{/block}

{block name="main:before"}
    {include file="home/brick/carousel.tpl" lazy=false}
{/block}

{block name='article:content' nocache}
    <h1 itemprop="name">{$home.name}</h1>
    <div itemprop="text">
        {$home.content}
    </div>
{/block}

{block name="main:after"}
    {include file="news/brick/last-news.tpl" scope="global" nocache}
{/block}