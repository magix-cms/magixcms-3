{extends file="layout.tpl"}
{block name="title"}{if $home.seoTitle}{$home.seoTitle}{else}{$home.name}{/if}{/block}
{block name="description"}{if $home.seoTitle}{$home.seoDescr}{elseif !empty($home.content)}{$home.content|truncate:100:'...'}{/if}{/block}
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