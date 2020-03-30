<div id="block-last-news" class="col-4 col-xs-6 col-sm-8 col-md-10 col-lg-4 block">
    {widget_news_data
        conf =[
            'context' =>  'last-news',
            'limit' => 2
            ]
        assign='newsFooterData'
    }
    <p class="h4"><a href="{$url}/{$lang}/news/" title="{#show_news#|ucfirst}">{#last_news#|ucfirst}</a></p>
    <div class="news-list-last row">
        {include file="news/loop/footer.tpl" data=$newsFooterData}
    </div>
</div>