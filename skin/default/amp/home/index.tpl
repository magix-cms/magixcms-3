{extends file="amp/layout.tpl"}
{block name="title"}{$home.seoTitle}{/block}
{block name="description"}{$home.seoDescr}{/block}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/home.min.css"}{/block}
{block name='body:id'}home{/block}
{block name='article:content'}
    <div class="container">
        <h1 itemprop="name">{$home.name}</h1>
    </div>
    <div itemprop="text">
        {amp_content content=$home.content}
    </div>
{/block}