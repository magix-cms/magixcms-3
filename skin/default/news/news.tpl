{extends file="news/index.tpl"}
{block name="title" nocache}{$news.seo.title}{/block}
{block name="description" nocache}{$news.seo.description}{/block}
{block name='body:id'}topicality{/block}
{block name="webType"}WebPage{/block}
{block name='article:class'} class="container clearfix"{/block}
{block name='article:type'}Article{/block}
{block name="styleSheet" nocache}
    {$css_files = ["newsRoot","news","gallery","lightbox","slider"]}
{/block}

{block name='article:header' nocache}
    {*<pre>{print_r($news)}</pre>*}
    <header id="header-news">
        <h1 itemprop="headline">{if $news.long_name != ''}{$news.long_name}{else}{$news.name}{/if}</h1>
        {if isset($news.date.event)}
        <div class="news-event">
            <meta itemprop="datePublished" content="{$news.date.publish.iso}">
            <meta itemprop="dateModified" content="{$news.date.update.iso}">
            <small>
            {if $news.date.event.end}
            <span>{#of#|ucfirst} </span>
            <time datetime="{$news.date.event.start.date}">{$news.date.event.start.timestamp|magic_date:"%e|%B|%Y"|replace:'|':'&nbsp;'}</time>
            <span> {#at#} </span>
            <time datetime="{$news.date.event.end.date}">{$news.date.event.end.timestamp|magic_date:"%e|%B|%Y"|replace:'|':'&nbsp;'}</time>
            {else}
                <span>{#the#|ucfirst} </span>
                <time datetime="{$news.date.event.start.date}">{$news.date.event.start.timestamp|magic_date:"%e|%B|%Y"|replace:'|':'&nbsp;'}</time>
            {/if}
            </small>
        </div>
        {else}
            <small>
                <time itemprop="datePublished" datetime="{$news.date.publish.iso}">{$news.date.publish.timestamp|magic_date:"%e|%B|%Y"|replace:'|':'&nbsp;'}</time>
                <meta itemprop="dateModified" content="{$news.date.update.iso}">
            </small>
        {/if}
    </header>
{/block}
{block name='article:content' nocache}
    <meta itemprop="mainEntityOfPage" content="{$url}{$news.uri}"/>
    <meta itemprop="wordCount" content="{$news.content|count_words}" />
    <meta itemprop="description" content="{$news.resume}"/>
    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
        <div id="publisher">
            <meta itemprop="name" content="{$companyData.name}">
            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <div id="logo-org">
                    <meta itemprop="url" content="{$url}/skin/{$theme}/img/logo/png/{#logo_img#}@300.png">
                    <meta itemprop="width" content="300">
                    <meta itemprop="height" content="84">
                </div>
            </div>
            <meta itemprop="image" itemscope itemtype="https://schema.org/ImageObject" itemref="logo-org">
        </div>
    </div>
    <div itemprop="author" itemscope itemtype="https://schema.org/{$companyData.type}" itemref="publisher"></div>
    <div itemprop="articleBody" class="text clearfix">
        <div class="desc">
            {*{if isset($news.img.name)}
                <a href="{$news.img.large.src}" class="img-zoom" title="{$news.img.title}" data-caption="{$news.img.caption}">
                    <figure itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                        <meta itemprop="url" content="{$url}{$news.img.large.src}" />
                        <meta itemprop="height" content="{$news.img.large.h}" />
                        <meta itemprop="width" content="{$news.img.large.w}" />
                        {include file="img/img.tpl" img=$news.img lazy=true}
                        {if $news.img.caption}
                            <figcaption>{$news.img.caption}</figcaption>
                        {/if}
                    </figure>
                </a>
            {/if}*}
        </div>
        <div class="row row-center">
            <div class="col-12{if is_array($news.imgs) && count($news.imgs) > 0} col-md-6 col-lg-7 col-xl-8{/if}">
                {if $news.lead}<p class="resume">{$news.lead}</p>{/if}
                {$news.content}
            </div>
            {if is_array($news.imgs) && count($news.imgs) > 0}
                <div class="col-12 col-md-6 col-lg-5 col-xl-4">
                    {include file="img/loop/gallery.tpl" imgs=$news.imgs}
                </div>
            {/if}
        </div>
    </div>
    {strip}
        {if !empty($news.tags)}
            <p class="tag-list">
                <span>{#news_theme#|ucfirst}&nbsp;:</span>
                {foreach $news.tags as $tag}
                    <span itemprop="articleSection">
            <a href="{$tag.url}" title="{#see_more_news_about#} {$tag.name|ucfirst}">
                {$tag.name}
            </a>
        </span>
                    {if !$tag@last}, {/if}
                {/foreach}
            </p>
        {/if}
    {/strip}
{/block}
{block name="article:after" nocache}
    {if $news.next || $news.prev}
    <nav class="container">
        <ul class="pager">
            {if $news.next}
                <li class="previous">
                    <a href="{$news.next.url}" class="pull-left" rel="next" title="{$news.next.title}"><span aria-hidden="true">&larr;</span> {#next_topic#}</a>
                </li>
            {/if}
            {if $news.prev}
                <li class="next">
                    <a href="{$news.prev.url}" class="pull-right" rel="prev" title="{$news.prev.title}">{#previous_topic#} <span aria-hidden="true">&rarr;</span></a>
                </li>
            {/if}
        </ul>
    </nav>
    {/if}
{/block}