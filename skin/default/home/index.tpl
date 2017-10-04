{autoload_i18n}
{#test#}
<h1 itemprop="name">{$home.name}</h1>
<div itemprop="text">
    {$home.content}
</div>
{widget_news_data
conf = [
'context' => 'all'
]
assign="pages"
}
<h2>Les actualités</h2>
<pre>
    {$pages|print_r}
</pre>
{*
<pre>
    {$setting|print_r}
</pre>
<pre>
    {$cssInliner|print_r}
</pre>
*}