{extends file="amp/news/index.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/topicality.min.css"}{/block}
{block name='body:id'}topicality{/block}
{block name="title"}{$news.seo.title}{/block}
{block name="description"}{$news.seo.description}{/block}
{block name="webType"}WebPage{/block}
{block name="amp-script"}
    {if $news.img.large.src}
    <script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
    {amp_components content=$news.content image=false}
    {else}
    {amp_components content=$news.content}
    {/if}
{/block}
{block name='article'}
    <article id="article" class="container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Article">
        {block name='article:content'}
            <header>
                {strip}
                    <h1 itemprop="headline">
                        {$news.name}
                        <small>
                            {*<span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                {#news_by#|ucfirst} <span itemprop="name">{$news.author}</span>
                            </span>*}
                            {*{#published_on#|ucfirst} *}<time itemprop="datePublished" datetime="{$news.date.publish}">{$news.date.publish|date_format:"%e %B %Y"}</time>
                            {*{if $news.date.publish|date_format:"%d-%m-%Y" != $news.date.register|date_format:"%d-%m-%Y"}
                                {#updated_on#} <time itemprop="dateModified" datetime="{$news.date.publish}">{$news.date.publish|date_format:"%e %B %Y"}</time>
                            {/if}*}
                        </small>
                    </h1>
                {/strip}
                <div class="row">
                    <div class="col-6">
                        <p class="label">{#news_by_theme#|ucfirst}</p>
                        <div class="dropdown-select">
                            <div class="dropdown">
                                <amp-accordion disable-session-states>
                                    <section>
                                        <header>
                                            <button class="btn btn-block btn-box btn-default" type="button">
                                                {if isset($tag)}{$tag.name}{else}{#choose_a_theme#|ucfirst}{/if}
                                                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                                            </button>
                                        </header>
                                        <div>
                                            <ul class="list-unstyled">
                                                {foreach $tags as $tag}
                                                    <li{if $tag.id eq $smarty.get.tag} class="active"{/if}>
                                                        {if $tag.id eq $smarty.get.tag}
                                                            {$tag.name}
                                                        {else}
                                                            <a href="{$tag.url}">{$tag.name}</a>
                                                        {/if}
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </section>
                                </amp-accordion>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <p class="label">{#news_by_date#|ucfirst}</p>
                        <div class="dropdown-select">
                            <div class="dropdown">
                                <amp-accordion disable-session-states>
                                    <section>
                                        <header>
                                            <button class="btn btn-block btn-box btn-default" type="button">
                                                {if $smarty.get.date}{$smarty.get.date|date_format:'%Y/%m/%d'}{elseif $monthName}{$smarty.get.year}/{if isset($smarty.get.month)}{$smarty.get.month}/{/if}{else}{#choose_a_date#|ucfirst}{/if}
                                                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                                            </button>
                                        </header>
                                        <amp-accordion class="nested-accordion">
                                            {foreach $archives as $year}
                                                <section>
                                                    <header>
                                                        <button class="btn btn-block btn-box btn-default" type="button">
                                                            <a href="{$year.url}" title="{$year.year}">{$year.year}</a>
                                                            <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                                            <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                                                        </button>
                                                    </header>
                                                    <div>
                                                        <ul class="list-unstyled">
                                                            {foreach $year.months as $month}
                                                                <li>
                                                                    <a href="{$month.url}" title="{$year.year|cat:"-%02d-01"|sprintf:$month['month']|date_format:'%B'|ucfirst}">
                                                                        {$year.year|cat:"-%02d-01"|sprintf:$month['month']|date_format:'%B'|ucfirst}
                                                                        <small>(&thinsp;{$month['nbr']}&thinsp;)</small></a>
                                                                </li>
                                                            {/foreach}
                                                        </ul>
                                                    </div>
                                                </section>
                                            {/foreach}
                                        </amp-accordion>
                                    </section>
                                </amp-accordion>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <meta itemprop="mainEntityOfPage" content="{$url}{$news.uri}"/>
            <meta itemprop="wordCount" content="{$news.content|count_words}" />
            <meta itemprop="description" content="{$news.resume}"/>
            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <div id="publisher">
                <meta itemprop="name" content="{$companyData.name}">
                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <div id="logo-org">
                    <meta itemprop="url" content="{$url}/skin/{$theme}/img/logo/{#logo_img#}">
                    <meta itemprop="width" content="269">
                    <meta itemprop="height" content="50">
                </div>
                </div>
                <meta itemprop="image" itemscope itemtype="https://schema.org/ImageObject" itemref="logo-org">
            </div>
            </div>
            <div itemprop="author" itemscope itemtype="https://schema.org/{$companyData.type}" itemref="publisher"></div>
            <div itemprop="articleBody">
                {if $news.img.small.src}
                    {*<figure{if $news.imgSrc.medium} itemprop="image" itemscope itemtype="http://schema.org/ImageObject"{/if}>
                        {if $news.imgSrc.small}
                            <meta itemprop="url" content="{$url}{$news.imgSrc.medium}" />
                            <meta itemprop="height" content="618" />
                            <meta itemprop="width" content="1000" />
                            <a href="{$news.imgSrc.medium}" class="img-zoom" title="{$news.name}" itemprop="thumbnail" itemscope itemtype="http://schema.org/ImageObject">
                                <img src="{$news.imgSrc.medium}" alt="{$news.name}" itemprop="contentUrl"/>
                            </a>
                        {else}
                            <img src="/skin/{$theme}/img/catalog/news-default.png" alt="{$news.name}" />
                        {/if}
                    </figure>*}
                    <figure itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
                        <meta itemprop="url" content="{$url}{$news.img.large.src}" />
                        <meta itemprop="height" content="{$news.img.large['h']}" />
                        <meta itemprop="width" content="{$news.img.large['w']}" />
                        {*<amp-img on="tap:lightbox1"
                                 role="button"
                                 tabindex="0"
                                 src="{$news.img.large.src}"
                                 alt="{$news.title}"
                                 title="{$news.title}"
                                 layout="responsive"
                                 width="{$news.img.large['w']}"
                                 height="{$news.img.large['h']}"></amp-img>*}
                        <amp-img on="tap:lightbox1"
                                 role="button"
                                 tabindex="0"
                                 src="{$news.img.large['src_webp']}"
                                 width="{$news.img.large['w']}"
                                 height="{$news.img.large['h']}"
                                 layout="responsive"
                                 alt="{$news.img.alt}"
                                 title="{$news.img.title}">
                            <amp-img on="tap:lightbox1"
                                     role="button"
                                     tabindex="0"
                                     alt="{$news.img.alt}"
                                     fallback
                                     title="{$news.img.title}"
                                     src="{$news.img.large['src']}"
                                     width="{$news.img.large['w']}"
                                     height="{$news.img.large['h']}"
                                     layout="responsive">
                            </amp-img>
                        </amp-img>
                        <figcaption class="hidden">{$news.img.caption}</figcaption>
                    </figure>
                    <amp-image-lightbox id="lightbox1" layout="nodisplay"></amp-image-lightbox>
                {/if}
                <div class="desc">
                    {amp_content content=$news.content}
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
    </article>
{/block}