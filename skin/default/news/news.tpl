{autoload_i18n}
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
</head>
<body id="news">
<h3>Données de l'actualité</h3>
<pre>{$news|print_r}</pre>
<h3>Données hreflang</h3>
<pre>{$hreflang|print_r}</pre>
{*<h3>Données du widget</h3>
<h2>{#ma_variable#}</h2>
{widget_cms_data
conf = [
    'context' => 'all'
]
assign="pages"
}

<h2>Les pages</h2>
<pre>
    {$pages|print_r}
</pre>*}
</body>