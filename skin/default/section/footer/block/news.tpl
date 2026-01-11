<div id="block-last-news" class="col-12 col-sm-5 col-md-6 block">
    {if !$lastNews}
        {widget_news_data
        conf =[
        'context' =>  'last-news',
        'limit' => 2
        ]
        assign='lastNews'
        }
    {/if}
    {if $lastNews}
        {$lastNews = array_slice($lastNews, 0, 2)}
    {/if}
    <p class="h4"><a href="{$url}/{$lang}/news/" title="{#show_news#|ucfirst}">{#last_news#|ucfirst}</a></p>
    <div class="news-list-last row">
        {include file="news/loop/footer.tpl" data=$lastNews}
    </div>
</div>