{extends file="layout.tpl"}
{block name='body:id'}error{/block}
{block name="title"}{/block}
{block name="description"}{/block}
{block name='article:content'}
    <h1 itemprop="name">{$getTitleHeader}</h1>
    <div class="text" itemprop="text">
        {$getTxtHeader}
    </div>
{/block}