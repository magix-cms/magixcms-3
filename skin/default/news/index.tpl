{extends file="layout.tpl"}
{block name='body:id'}news{/block}
{block name='body:class'}news-page{/block}
{block name="title"}{$rootSeo['title']}{/block}
{block name="description"}{$rootSeo['description']}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article id="article"{block name="article:class"} class="container"{/block} itemprop="mainEntity" itemscope itemtype="http://schema.org/{block name='article:type'}Periodical{/block}">
        {block name='article:header'}
        <header id="header-news">
            <div class="row">
                {strip}
                <div class="col-4 col-xs-6 col-sm-8 col-md-5 col-lg-6">
                    {block name='article:title'}
                    {strip}<h1 itemprop="name">
                        {#news#|ucfirst}
                        {if isset($tag)}<small> - <span itemprop="about">{$tag.name|ucfirst}</span></small>{/if}
                        {if isset($smarty.get.date) || isset($smarty.get.year) || isset($monthName)}<small> - <span>{if $smarty.get.date}{$smarty.get.date|date_format:'%e %B %Y'}{elseif isset($monthName)}{$monthName} {$smarty.get.year}{elseif isset($smarty.get.year)}{$smarty.get.year}{/if}</span></small>{/if}
                    </h1>{/strip}
                    {/block}
                </div>
                {/strip}
                <div class="col-4 col-xs-6 col-sm-8 col-md-5 col-lg-6">
                    <div class="row">
                        {if !empty($tags)}
                        <div class="col-2 col-xs-3 col-sm-4 col-md-5 col-lg-6">
                            {*<p class="label">{#news_by_theme#|ucfirst}</p>*}
                            <div class="dropdown filter">
                                {strip}<button class="btn btn-block btn-box btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span>{if isset($tag)}{$tag.name}{else}{#news_by_theme#|ucfirst}{/if}</span>
                                    <span class="show-more"><i class="material-icons ico ico-arrow_drop_down"></i></span>
                                    <span class="show-less"><i class="material-icons ico ico-arrow_drop_up"></i></span>
                                </button>{/strip}
                                <ul class="dropdown-menu">
                                    <li><a href="{$url}/{$lang}/news">{#all_news#}</a></li>
                                    {foreach $tags as $tag}
                                    <li{if $tag.id eq $smarty.get.tag} class="active"{/if}>{if $tag.id eq $smarty.get.tag}{$tag.name}{else}<a href="{$tag.url}">{$tag.name}</a>{/if}</li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                        {/if}
                        {if $news}
                        <div class="col-2 col-xs-3 col-sm-4 col-md-5 col-lg-6">
                            {*<p class="label">{#news_by_date#|ucfirst}</p>*}
                            <div class="dropdown filter">
                                {strip}<button class="btn btn-block btn-box btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span>{if $smarty.get.date}{$smarty.get.date|date_format:'%Y/%m/%d'}{elseif $smarty.get.year}{$smarty.get.year}/{if isset($smarty.get.month)}{$smarty.get.month}/{/if}{else}{#news_by_date#|ucfirst}{/if}</span>
                                    <span class="show-more"><i class="material-icons ico ico-arrow_drop_down"></i></span>
                                    <span class="show-less"><i class="material-icons ico ico-arrow_drop_up"></i></span>
                                    </button>{/strip}
                                <ul class="dropdown-menu">
                                    <li><a href="{$url}/{$lang}/news">{#all_news#}</a></li>
                                    {foreach $archives as $year}
                                        <li class="panel">
{*                                            <div class="header">*}
                                            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#mth-{$year.year}">
                                                <span class="show-more"><i class="material-icons ico ico-arrow_drop_down"></i></span>
                                                <span class="show-less"><i class="material-icons ico ico-arrow_drop_up"></i></span>
                                            </button>
                                            <a href="{$year.url}" title="{$year.year}" {*data-toggle="collapse" data-target="#mth-{$year.year}"*}>{$year.year}</a>
                                            <ul id="mth-{$year.year}" class="collapse">
                                            {foreach $year.months as $month}
                                                {strip}<li>
                                                    <a href="{$month.url}" title="{$year.year|cat:"-%02d-01"|sprintf:$month['month']|date_format:'%B'|ucfirst}">{$year.year|cat:"-%02d-01"|sprintf:$month['month']|date_format:'%B'|ucfirst} <small>(&thinsp;{$month['nbr']}&thinsp;)</small></a>
                                                </li>{/strip}
                                            {/foreach}
                                            </ul>
{*                                            </div>*}
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </header>
        {/block}
        {block name='article:content'}
            {if $news}
            <div class="news-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                <div class="tile-list row row-center">
                    {include file="news/loop/news.tpl" data=$news classCol='news-tile col-4 col-xs-3 col-sm-4 col-md-th col-lg-4 col-xl-3'}
                </div>
            </div>
            {/if}
        {/block}
    </article>
    {if $nbp > 1}
        <div class="container">
            {include file="section/brick/pagination/number.tpl" nbp=$nbp}
            {*{include file="section/brick/pagination/pager.tpl" nbp=$nbp}*}
        </div>
    {/if}
{/block}