{extends file="news/index.tpl"}
{block name="title" nocache}{if isset($smarty.get.page)}{$tag.seo.title|cat:' - page '|cat:$smarty.get.page}{else}{$tag.seo.title}{/if}{/block}
{block name="description" nocache}{if isset($smarty.get.page)}{$tag.seo.description|cat:' - page '|cat:$smarty.get.page}{else}{$tag.seo.description}{/if}{/block}
{block name='body:id'}news-tag{/block}
{block name='article:content' append}
    <div itemprop="isPartOf" itemscope itemtype="http://schema.org/Periodical" itemid="#periodical">
        <meta itemprop="name" content="{#news#|ucfirst}"/>
        <meta itemprop="url" content="{$url}/{$lang}/news/"/>
    </div>
{/block}