{widget_news_data
conf = [
'context' => 'all',
'limit' => 3
]
assign="lastNews" scope="global"}
{if $lastNews}
    <div id="last-news" class="container" itemprop="hasPart" itemscope itemtype="http://schema.org/Periodical">
        <div class="news-list" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
            <p class="h3">{#last_news#|ucfirst}</p>
            <div class="tile-list row row-center">
                {include file="news/loop/news.tpl" data=$lastNews classCol='news-tile col-12 col-xs-6 col-sm-4'}
            </div>
        </div>
    </div>
{/if}