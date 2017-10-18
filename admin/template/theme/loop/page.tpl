{*{foreach $links as $link}
    <option value="{$link.id}">{$link.name}</option>
    {if $link.child}
        {if $level > 1}
            </optgroup>
        {/if}
        <optgroup label="-" {if $level} style="margin-left: {$level * 15}px;" {/if}>
        {include file="theme/loop/page.tpl" links=$link.child level=($level +1)}
    {/if}
{/foreach}
{if $level > 1}</optgroup>{/if}*}

{foreach $links as $link}
    <li class="filter-item items" data-filter="{$link.name}" data-value="{$link.id}" data-id="{$link.id}">
        {$link.name|ucfirst}
        {if $link.child}
            <li class="optgroup">
                {*<span class="optgroup-header">Sous-page(s)</span>*}
                <ul class="list-unstyled">
                    {include file="theme/loop/page.tpl" links=$link.child}
                </ul>
            </li>
        {/if}
    </li>
{/foreach}