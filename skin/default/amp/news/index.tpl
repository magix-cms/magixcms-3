{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/news.min.css"}{/block}
{block name='body:id'}news{/block}
{block name='body:class'}news-page{/block}
{block name="title"}{$rootSeo['title']}{/block}
{block name="description"}{$rootSeo['description']}{/block}
{block name="webType"}CollectionPage{/block}
{block name='article'}
    <article id="article" class="container" itemprop="mainEntity" itemscope itemtype="http://schema.org/Periodical">
        {block name='article:content'}
            <header>
            {strip}
            <h1 itemprop="name">
                {#news#|ucfirst}
                {if isset($tag)}<small> - <span itemprop="about">{$tag.name|ucfirst}</span></small>{/if}
                {if isset($smarty.get.date) || isset($monthName)}<small> - <span>{if $smarty.get.date}{$smarty.get.date|date_format:'%e %B %Y'}{else}{if isset($monthName)}{$monthName} {/if}{$smarty.get.year}{/if}</span></small>{/if}
            </h1>
            {/strip}
            <div class="row">
                {if !empty($tags)}
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
                                                <li{if $tag.id eq $smarty.get.tag} class="active"{/if}>{if $tag.id eq $smarty.get.tag}{$tag.name}{else}<a href="{$tag.url}">{$tag.name}</a>{/if}</li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                </section>
                            </amp-accordion>
                        </div>
                    </div>
                </div>
                {/if}
                {if $news}
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
                {/if}
            </div>
            </header>
            {if $news}
                <div itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
                    <div class="news-list">
                        <div class="section-block">
                            <div class="row">
                                <div>
                                    {include file="amp/news/loop/news.tpl" data=$news classCol='news-tile'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
        {/block}
    </article>
{/block}