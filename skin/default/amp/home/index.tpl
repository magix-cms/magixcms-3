{extends file="layout.tpl"}
{block name='body:id'}home{/block}
{block name='article:content'}
    page amp
    {#test#}
    <h1 itemprop="name">{$home.name}</h1>
    <div itemprop="text">
        {$home.content}
    </div>
    {*<amp-img src="welcome.jpg" alt="Welcome" height="400" width="800"></amp-img>*}
{/block}