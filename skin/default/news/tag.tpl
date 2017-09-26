{widget_news_data
conf = [
    'context'   => 'tag',
    'select'    =>  {$smarty.get.tag}
]
assign="pages"
}

<h2>Les actualités</h2>
<pre>
    {$pages|print_r}
</pre>
<h2>Les tags</h2>
{widget_news_data
conf = [
'context'   => 'tags'
]
assign="tags"
}
<pre>
    {$tags|print_r}
</pre>
</body>