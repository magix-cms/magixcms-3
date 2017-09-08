{autoload_i18n}
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
</head>
<body id="pages">
<h3>Données de la page</h3>
<pre>{$pages|print_r}</pre>
<h3>Données hreflang</h3>
<pre>{$hreflang|print_r}</pre>
<h3>Données du widget</h3>
<h2>{#ma_variable#}</h2>
{widget_cms_data
conf = [
    'context' => 'all'
]
assign="pages"
}
{*'select' => ["fr" => "31"], 'exclude' => ["fr" => "31,16"] *}
<h2>Les pages</h2>
<pre>
    {$pages|print_r}
</pre>
</body>