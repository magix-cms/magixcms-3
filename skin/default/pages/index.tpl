{*<pre>{$pages|print_r}</pre>
<pre>{$hreflang|print_r}</pre>*}
{autoload_i18n}
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