{extends file="layout.tpl"}
{block name="title" nocache}{if isset($smarty.get.page)}{$rootSeo['title']|cat:' - page '|cat:$smarty.get.page}{else}{$rootSeo['title']}{/if}{/block}
{block name="description" nocache}{if isset($smarty.get.page)}{$rootSeo['description']|cat:' - page '|cat:$smarty.get.page}{else}{$rootSeo['description']}{/if}{/block}
{block name='body:id'}news{/block}
{block name='body:class'}news-page{/block}
{block name="webType"}CollectionPage{/block}
{block name="styleSheet" nocache}
    {$css_files = ["newsRoot"]}
{/block}

{block name='article'}
    <article id="article"{block name="article:class"} class="container"{/block} itemprop="mainEntity" itemscope itemtype="http://schema.org/{block name='article:type'}Periodical{/block}">
        {block name='article:header'}
        <header id="header-news">
            <div class="row">
                {strip}
                <div class="col-12 col-md-6">
                    {block name='article:title' nocache}
                    {strip}<h1 itemprop="name">
                        {#news#|ucfirst}
                        {if isset($tag)}<small> - <span itemprop="about">{$tag.name|ucfirst}</span></small>{/if}
                        {if isset($smarty.get.page)}<small> - page {$smarty.get.page}</small>{/if}
                        {if isset($smarty.get.date) || isset($smarty.get.year) || isset($monthName)}<small> - <span>{if $smarty.get.date}{$smarty.get.date|date_format:'%e %B %Y'}{elseif isset($monthName)}{$monthName} {$smarty.get.year}{elseif isset($smarty.get.year)}{$smarty.get.year}{/if}</span></small>{/if}
                    </h1>{/strip}
                    {/block}
                </div>
                {/strip}
                <div class="col-12 col-md-6">
                    <div class="row">
                        {if !empty($tags)}
                        <div class="col-6">
                            {*<p class="label">{#news_by_theme#|ucfirst}</p>*}
                            <div class="dropdown filter">
                                {strip}<button class="btn btn-block btn-box btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span>{if isset($tag)}{$tag.name}{else}{#news_by_theme#|ucfirst}{/if}</span>
                                    <span class="show-more"><i class="material-icons ico ico-arrow_drop_down"></i></span>
                                    <span class="show-less"><i class="material-icons ico ico-arrow_drop_up"></i></span>
                                </button>{/strip}
                                <ul class="dropdown-menu">
                                    <li><a href="{$url}/{$lang}/news">{#all_news#}</a></li>
                                    {nocache}
                                    {foreach $tags as $tag}
                                    <li{if $tag.id eq $smarty.get.tag} class="active"{/if}><a href="{$tag.url}">{$tag.name}</a></li>
                                    {/foreach}
                                    {/nocache}
                                </ul>
                            </div>
                        </div>
                        {/if}
                        {if $news}
                        <div class="col-6">
                            {*<p class="label">{#news_by_date#|ucfirst}</p>*}
                            <div class="dropdown filter">
                                {strip}<button class="btn btn-block btn-box btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span>{if $smarty.get.date}{$smarty.get.date|date_format:'%Y/%m/%d'}{elseif $smarty.get.year}{$smarty.get.year}/{if isset($smarty.get.month)}{$smarty.get.month}/{/if}{else}{#news_by_date#|ucfirst}{/if}</span>
                                    <span class="show-more"><i class="material-icons ico ico-arrow_drop_down"></i></span>
                                    <span class="show-less"><i class="material-icons ico ico-arrow_drop_up"></i></span>
                                    </button>{/strip}
                                <ul class="dropdown-menu">
                                    <li><a href="{$url}/{$lang}/news">{#all_news#}</a></li>
                                    {nocache}
                                    {foreach $archives as $year}
                                        <li class="panel{if isset($smarty.get.year) && $smarty.get.year == $year.year} active{/if}">
{*                                            <div class="header">*}
                                            <button class="btn" type="button" data-toggle="collapse" data-target="#mth-{$year.year}">
                                                <span class="show-more"><i class="material-icons ico ico-arrow_drop_down"></i></span>
                                                <span class="show-less"><i class="material-icons ico ico-arrow_drop_up"></i></span>
                                            </button>
                                            <a href="{$year.url}" title="{$year.year}" {*data-toggle="collapse" data-target="#mth-{$year.year}"*}>{$year.year}</a>
                                            <ul id="mth-{$year.year}" class="collapse">
                                            {foreach $year.months as $month}
                                                {$strdate = $year.year|cat:"-%02d-01"}
                                                {strip}<li{if ($smarty.get.year && $smarty.get.year == $year.year) && $smarty.get.month && $smarty.get.month == '%02d'|string_format:$month['month']} class="active"{/if}>
                                                    <a href="{$month.url}" title="{$month['month']|string_format:$strdate|magic_date:'%B'|ucfirst}">{$month['month']|string_format:$strdate|magic_date:'%B'|ucfirst} <small>(&thinsp;{$month['nbr']}&thinsp;)</small></a>
                                                    </li>{/strip}
                                            {/foreach}
                                            </ul>
{*                                            </div>*}
                                        </li>
                                    {/foreach}
                                    {/nocache}
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
                <div class="tile-list row">
                    {include file="news/loop/news.tpl" data=$news classCol='news-tile col-12 col-xs-6 col-md-4' nocache}
                </div>
            </div>
            {/if}
        {/block}
    </article>
    {if $nbp.nbp > 1}
        <div class="container">
            {include file="section/brick/pagination/number.tpl" nbp=$nbp nocache}
            {*{include file="section/brick/pagination/pager.tpl" nbp=$nbp}*}
        </div>
    {/if}
{/block}