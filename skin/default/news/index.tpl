{extends file="layout.tpl"}
{block name='body:id'}news{/block}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{#news#}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{#last_news#}]}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article id="article" itemprop="mainEntity" itemscope itemtype="http://schema.org/Periodical">
        {block name='article:content'}
            <div class="container">
                <header>
                    <div class="row">
                        {strip}
                            <div class="col-12 col-md-6">
                                <h1 itemprop="name">
                                    {#news#|ucfirst}
                                    {if isset($tag)}<small> - <span itemprop="about">{$tag.name|ucfirst}</span></small>{/if}
                                    {if isset($smarty.get.date) || isset($monthName)}<small> - <span>{if $smarty.get.date}{$smarty.get.date|date_format:'%e %B %Y'}{else}{if isset($monthName)}{$monthName} {/if}{$smarty.get.year}{/if}</span></small>{/if}
                                </h1>
                            </div>
                        {/strip}
                        <div class="col-6 col-md-3">
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
                        <div class="col-6 col-md-3">
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
            </div>
            {if $news}
                <div class="container-fluid" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    <div class="news-list">
                        <div class="section-block">
                            <div class="row">
                                <div class="container">
                                    <div class="row">
                                        <div class="tile-list">
                                            {include file="news/loop/news.tpl" data=$news classCol='news-tile'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
    {if $nbp > 1}
        <section class="container">
            {include file="section/brick/pagination/number.tpl" nbp=$nbp}
            {*{include file="section/brick/pagination/pager.tpl" nbp=$nbp}*}
        </section>
    {/if}
{/block}