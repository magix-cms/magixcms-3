{extends file="layout.tpl"}
{block name="title" nocache}{$home.seo.title}{/block}
{block name="description" nocache}{$home.seo.description}{/block}
{block name='body:id'}home{/block}
{block name="styleSheet"}
    {$css_files = [
        "/skin/{$theme}/css/home{if $setting.mode.value !== 'dev'}.min{/if}.css",
        "/skin/{$theme}/css/lightbox{if $setting.mode.value !== 'dev'}.min{/if}.css",
        "/skin/{$theme}/css/slider{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
{/block}

{block name="main:before"}
    {include file="home/brick/carousel.tpl"}
{/block}

{block name='article:content' nocache}
    <h1 itemprop="name">{$home.name}</h1>
    <div itemprop="text">
        {$home.content}
    </div>
{/block}

{block name="main:after"}
    {include file="news/brick/last-news.tpl" nocache}
{/block}