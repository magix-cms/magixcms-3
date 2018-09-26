{if isset($nbp)}
<nav>
    <ul class="pager">
        {if $smarty.get.page != 1 && isset($smarty.get.page)}
        <li class="previous">
            <a href="{$url}{$smarty.server.REQUEST_URI|replace:{'page/'|cat:$smarty.get.page}:''}{if $smarty.get.page > 2}page/{$smarty.get.page - 1}{/if}" rel="prev">
                <span aria-hidden="true">&larr;</span> {#news_previous#}
            </a>
        </li>
        {/if}
        {if $smarty.get.page != $nbp || !isset($smarty.get.page)}
        <li class="next">
            <a href="{$url}{if $smarty.get.page}{$smarty.server.REQUEST_URI|replace:{'page/'|cat:$smarty.get.page}:''}{else}{$smarty.server.REQUEST_URI}{/if}page/{if !isset($smarty.get.page)}2{else}{$smarty.get.page + 1}{/if}" rel="next">
                {#news_next#} <span aria-hidden="true">&rarr;</span>
            </a>
        </li>
        {/if}
    </ul>
</nav>
{/if}