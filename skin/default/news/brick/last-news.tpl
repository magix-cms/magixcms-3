{widget_news_data
conf = [
'context' => 'all',
'limit' => 3
]
assign="news"
}
{if $news}
    <section id="last-news" class="container-fluid">
        <div class="row">
            <div class="container">
                <h3>{#last_news#|ucfirst}</h3>
            </div>
        </div>
        <div class="news-list">
            <div class="section-block">
                <div class="row">
                    <div class="container">
                        <div class="row">
                            <div class="tile-list">
                                {include file="news/loop/news.tpl" data=$news classCol="news-tile"}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{/if}