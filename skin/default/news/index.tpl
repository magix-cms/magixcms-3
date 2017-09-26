{autoload_i18n}
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
</head>
<body id="news">
<h2>{#ma_variable#}</h2>
{widget_news_data
conf = [
    'context' => 'all'
]
assign="pages"
}
{*'select' => ["fr" => "31"], 'exclude' => ["fr" => "31,16"] *}
<h2>Les actualités</h2>
<pre>
    {$pages|print_r}
</pre>
</body>