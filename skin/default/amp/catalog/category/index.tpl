{autoload_i18n}
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
</head>
<body id="pages">
<h3>Données de la page</h3>
<pre>{$cat|print_r}</pre>
<h3>Données hreflang</h3>
<pre>{$hreflang|print_r}</pre>
{*<h3>Widgets </h3>
{widget_catalog_data
conf =[
'context' =>  'category'
]
assign='categoryData'
}
<pre>{$categoryData|print_r}</pre>
*}
<h3>Widgets </h3>
{widget_catalog_data
conf =[
'context' =>  'product'
]
assign='productData'
}
<pre>{$productData|print_r}</pre>
</body>