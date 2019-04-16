{extends file="amp/layout.tpl"}
{block name="title"}{$home.seo.title}{/block}
{block name="description"}{$home.seo.description}{/block}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/home.min.css"}{/block}
{block name='body:id'}home{/block}
{block name="amp-script"}
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    {amp_components content=$home.content carousel=false}
{/block}
{block name="main:before"}
    {include file="home/brick/carousel.tpl" amp=true}
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
    <section id="last-news" class="container" itemprop="hasPart" itemscope itemtype="http://schema.org/Periodical">
        <div class="news-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
            <h3>{#last_news#|ucfirst}</h3>
            <div class="tile-list row">
                {include file="amp/news/loop/news.tpl" data=$news classCol="news-tile col-12"}
            </div>
        </div>
    </section>
    {/if}
{/block}