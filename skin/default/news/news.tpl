{extends file="news/index.tpl"}
{block name='body:id'}topicality{/block}
{block name="title"}{$news.seo.title}{/block}
{block name="description"}{$news.seo.description}{/block}
{block name="webType"}WebPage{/block}
{block name='article:class'} class="container clearfix"{/block}
{block name='article:type'}Article{/block}
{block name='article:header'}
    <header id="header-news">
        <h1 itemprop="headline">{$news.name}</h1>
        <small>
            <time itemprop="datePublished" datetime="{$news.date.publish}">{$news.date.publish|date_format:"%e|%B|%Y"|replace:'|':'&nbsp;'}</time>
            <meta itemprop="dateModified" content="{$news.date.update}">
        </small>
    </header>
{/block}
{block name='article:content'}
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
    <div itemprop="articleBody">
        <div class="desc">
            {if isset($news.img.name)}
                <a href="{$news.img.large.src}" class="img-zoom" title="{$news.img.title}" data-caption="{$news.img.caption}">
                    <figure itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                        <meta itemprop="url" content="{$url}{$news.img.large.src}" />
                        <meta itemprop="height" content="{$news.img.large.h}" />
                        <meta itemprop="width" content="{$news.img.large.w}" />
                        {*<img class="img-responsive" src="{$news.img.medium.src}" alt="{$news.img.alt}" title="{$news.img.title}" />*}
                        {strip}<picture>
                            <!--[if IE 9]><video style="display: none;"><![endif]-->
                            <source type="image/webp" sizes="{$news.img.medium['w']}px" srcset="{$news.img.medium['src_webp']} {$news.img.medium['w']}w">
                            <source type="{$news.img.medium.ext}" sizes="{$news.img.medium['w']}px" srcset="{$news.img.medium['src']} {$news.img.medium['w']}w">
                            <!--[if IE 9]></video><![endif]-->
                            <img data-src="{$news.img.medium['src']}" width="{$news.img.medium['w']}" height="{$news.img.medium['h']}" alt="{$news.img.alt}" title="{$news.img.title}" class="img-responsive lazyload" />
                            </picture>{/strip}
                        {if $news.img.caption}
                            <figcaption>{$news.img.caption}</figcaption>
                        {/if}
                    </figure>
                </a>
            {/if}
            {if $news.lead}<p class="resume">{$news.lead}</p>{/if}
            {$news.content}
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
{block name="article:after"}
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