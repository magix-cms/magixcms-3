{widget_news_data
conf = [
'context' => 'all',
'limit' => 3
]
assign="news"
}
{if $news}
    <section id="last-news" class="container-fluid" itemprop="hasPart" itemscope itemtype="http://schema.org/Periodical">
        <div class="news-list row" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
            <div class="container">
                <h3>{#last_news#|ucfirst}</h3>
                <div class="tile-list row row-center">
                    {include file="news/loop/news.tpl" data=$news classCol='news-tile col-12 col-xs-8 col-sm-6 col-md-4 col-xl-3'}
                </div>
            </div>
        </div>
    </section>
{/if}