{widget_news_data
    conf = [
        'context' => 'all',
        'limit' => 3
        ]
    assign="news"
}
<section id="last-news" class="section-block">
    <div class="container">
        <h3>{#last_news#|ucfirst}</h3>
        <div class="news-list">
            {if $newsData}
            {include file="news/loop/news.tpl" data=$newsData classCol='col-ph-12 col-sm-6'}
            {/if}
        </div>
    </div>
</section>