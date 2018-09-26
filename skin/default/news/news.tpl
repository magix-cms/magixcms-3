{extends file="news/index.tpl"}
{block name='body:id'}topicality{/block}
{block name="title"}{seo_rewrite conf=['level'=>'record','type'=>'title','default'=>{$news.title}] record={$news.title}}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'record','type'=>'description','default'=>{$news.resume}] record={$news.title}}{/block}
{block name="webType"}WebPage{/block}
{block name='article'}
    <article id="article" class="container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Article">
        {block name='article:content'}
            <header>
                {strip}
                    <h1 itemprop="headline">
                        {$news.title}
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
                        <div class="dropdown">
                            <button class="btn btn-block btn-box btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                {if isset($tag)}{$tag.name}{else}{#choose_a_theme#|ucfirst}{/if}
                                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                            </button>
                            <ul class="dropdown-menu">
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
                    </div>
                    <div class="col-6">
                        <p class="label">{#news_by_date#|ucfirst}</p>
                        <div class="dropdown">
                            <button class="btn btn-block btn-box btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                {if $smarty.get.date}{$smarty.get.date|date_format:'%Y/%m/%d'}{elseif $monthName}{$smarty.get.year}/{if isset($smarty.get.month)}{$smarty.get.month}/{/if}{else}{#choose_a_date#|ucfirst}{/if}
                                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach $archives as $year}
                                    <li class="dropdown-header">
                                        <button class="btn btn-block btn-box btn-default" type="button" data-toggle="collapse" data-target="#mth-{$year.year}">
                                            <a href="{$year.url}" title="{$year.year}" data-toggle="collapse" data-target="#mth-{$year.year}">{$year.year}</a>
                                            <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                            <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                                        </button>
                                        <ul id="mth-{$year.year}" class="collapse dropdown-menu">
                                            {foreach $year.months as $month}
                                                <li>
                                                    <a href="{$month.url}" title="{$year.year|cat:"-%02d-01"|sprintf:$month['month']|date_format:'%B'|ucfirst}">
                                                        {$year.year|cat:"-%02d-01"|sprintf:$month['month']|date_format:'%B'|ucfirst}
                                                        <small>(&thinsp;{$month['nbr']}&thinsp;)</small></a>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </li>
                                {/foreach}
                            </ul>
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
                {if $news.imgSrc.small}
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
                        <meta itemprop="url" content="{$url}{$news.imgSrc.large}" />
                        <meta itemprop="height" content="618" />
                        <meta itemprop="width" content="1000" />
                        <a href="{$news.imgSrc.large}" class="img-zoom" title="{$news.title}">
                            <img class="img-responsive" src="{$news.imgSrc.medium}" alt="{$news.title}" title="{$news.title}" />
                        </a>
                    </figure>
                {/if}
                <div class="desc">
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
        <nav>
            <ul class="pager">
                {if $news.next}
                <li class="previous">
                    <a href="{$news.next.url}" class="pull-left btn btn-link" rel="next" title="{$news.next.title}">{#next_topic#}</a>
                </li>
                {/if}
                {if $news.prev}
                <li class="next">
                    <a href="{$news.prev.url}" class="pull-right btn btn-link" rel="prev" title="{$news.prev.title}">{#previous_topic#}</a>
                </li>
                {/if}
            </ul>
        </nav>
    </article>
{/block}