{extends file="layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}css/home.min.css"}{/block}
{block name='body:id'}home{/block}
{block name='article:content'}
    <div class="container">
        <h1 itemprop="name">{$home.name}</h1>
    </div>
    <div itemprop="text">
        {amp_img content=$home.content}
    </div>
{/block}