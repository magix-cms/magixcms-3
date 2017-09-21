{autoload_i18n}
<head id="meta">{* Document meta *}
    <meta charset="utf-8">
</head>
<body id="pages">
<h3>Données racine</h3>
<pre>{$root|print_r}</pre>
{widget_catalog_data
    conf =[
        'context' =>  'category'
    ]
    assign='categoryData'
}
<pre>{$categoryData|print_r}</pre>

</body>