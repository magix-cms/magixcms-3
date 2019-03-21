{if isset($nbp)}
{if isset($smarty.get.page)}
    {$current = $smarty.get.page}
{else}
    {$current = 1}
{/if}
    {$request = $smarty.server.REQUEST_URI}
    {$search = strpos($request,'page')}
    {if $search}
        {$request = substr($request,0,$search)}
    {/if}
    {$request = $request|replace:'?':''}
{capture name="searchform"}
    {strip}
        <form action="{$url}{$request}" class="form-inline">
            <div class="form-group">
                <label for="getpage" class="sr-only">Page</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="page" id="getpage" placeholder="Accéder à la page ...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="material-icons">search</i></button>
                    </span>
                </div>
            </div>
        </form>
    {/strip}
{/capture}
<nav class="text-center">
    <ul class="pagination pager">
        <li{if $current == 1} class="disabled"{/if}>
            <a href="{$url}{$request}{if $smarty.get.page && $smarty.get.page > 2}page/{$smarty.get.page - 1}{/if}" aria-label="Previous">
                <i class="material-icons" aria-hidden="true">arrow_left</i>
            </a>
        </li>
        <li{if $smarty.get.page === '1' || !$smarty.get.page } class="active"{/if}>
            <a href="{$url}{$request}page/1">1</a>
        </li>
        {$prevform = false}
        {$nextform = false}
        {for $i = 2 to $nbp - 1}
            {if $nbp > 10}
                {if ($current - 2) < 1}
                    {$limit2 = ($current + 2) + (3 - $current) }
                {else}
                    {$limit2 = ($current + 2) }
                {/if}
                {if ($current + 2) > $nbp}
                    {$limit1 = ($current - 2) - ($current + 2 - $nbp) }
                {else}
                    {$limit1 = ($current - 2) }
                {/if}
                {if $i >= $limit1 && $i <= $limit2}
                    <li{if $current == $i} class="active"{/if}>
                        <a href="{$url}{$request}page/{$i}">{$i}</a>
                    </li>
                {else}
                    {if $i < $limit1 && !$prevform}
                        {$prevform = true}
                    <li class="disabled">
                        <a href="#">
                            &hellip;
                        </a>
                    </li>
                    {/if}
                    {if $i > $limit2 && !$nextform}
                        {$nextform = true}
                    <li class="disabled">
                        <a href="#">
                            &hellip;
                        </a>
                    </li>
                    {/if}
                {/if}
            {else}
                <li{if $current == $i} class="active"{/if}>
                    <a href="{$url}{$request}page/{$i}">{$i}</a>
                </li>
            {/if}
        {/for}
        <li{if $current == $nbp} class="active"{/if}>
            <a href="{$url}{$request}page/{$nbp}">{$nbp}</a>
        </li>
        {if $nbp > 10}
        <li>
            <a class="btn btn-default" data-container="body" data-toggle="popover" data-placement="top" title="Rechercher une page" data-html="true" data-content='{$smarty.capture.searchform}'>
                <i class="material-icons">search</i>
            </a>
        </li>
        {/if}
        <li{if $current == $nbp} class="disabled"{/if}>
            <a href="{$url}{$request}{if $smarty.get.page != $nbp}page/{if $smarty.get.page}{$smarty.get.page + 1}{else}2{/if}{/if}" aria-label="Next">
                <i class="material-icons" aria-hidden="true">arrow_right</i>
            </a>
        </li>
    </ul>
</nav>
{/if}