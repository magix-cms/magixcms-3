{extends file="amp/layout.tpl"}
{block name="title"}{if $home.seoTitle}{$home.seoTitle}{else}{$home.name}{/if}{/block}
{block name="description"}{if $home.seoTitle}{$home.seoDescr}{elseif !empty($home.content)}{$home.content|truncate:100:'...'}{/if}{/block}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/home.min.css"}{/block}
{block name='body:id'}home{/block}
{block name="amp-script"}
    {amp_components content=$home.content}
{/block}
{block name='article'}
    <article class="container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        {block name='article:content'}
            <h1 itemprop="name">{$home.name}</h1>
            <div class="text" itemprop="text">
                {amp_content content=$home.content}
            </div>
        {/block}
    </article>
{/block}
{block name="main:after"}
    {widget_news_data
        conf = [
            'context' => 'all',
            'limit' => 3
            ]
        assign="news"
    }
    {if $news}
    <section id="last-news" class="container">
        <h3>{#last_news#|ucfirst}</h3>
        <div class="news-list">
            <div class="section-block">
                <div class="row">
                    <div>
                    {include file="amp/news/loop/news.tpl" data=$news classCol="news-tile"}
                    </div>
                </div>
            </div>
        </div>
    </section>
    {/if}
{/block}