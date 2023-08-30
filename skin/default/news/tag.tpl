{extends file="news/index.tpl"}
{block name="title" nocache}{$tag.seo.title}{/block}
{block name="description" nocache}{$tag.seo.description}{/block}
{block name='body:id'}news-tag{/block}
{block name='article:content' append}
    <div itemprop="isPartOf" itemscope itemtype="http://schema.org/Periodical" itemid="#periodical">
        <meta itemprop="name" content="{#news#|ucfirst}"/>
        <meta itemprop="url" content="{$url}/{$lang}/news/"/>
    </div>
{/block}