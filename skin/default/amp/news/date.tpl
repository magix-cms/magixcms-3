{extends file="amp/news/index.tpl"}
{block name='body:id'}news-date{/block}
{block name='article:content' append}
    <div itemprop="isPartOf" itemscope itemtype="http://schema.org/Periodical" itemid="#periodical">
        <meta itemprop="name" content="{#news#|ucfirst}"/>
        <meta itemprop="url" content="{geturl}/{getlang}/amp/news/"/>
    </div>
{/block}