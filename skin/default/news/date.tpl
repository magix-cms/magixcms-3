{widget_news_data
conf = [
    'context' => 'all',
    'filter' => ['year'=>{$smarty.get.year},'month'=>{$smarty.get.month}]
]
assign="pages"
}

<h2>Les actualités</h2>
<pre>
    {$pages|print_r}
</pre>
</body>