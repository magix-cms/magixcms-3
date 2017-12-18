{extends file="layout.tpl"}
{block name='article:header'}
    <h1>Analysis</h1>
{/block}
{block name='article:content'}
    {$getBuildItems|print_r}
{/block}