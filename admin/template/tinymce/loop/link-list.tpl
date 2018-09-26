{foreach $data as $link}
<li class="filter-item items" data-filter="{$link[{'name_'|cat:$type}]}" data-value="{$link[{'id_'|cat:$type}]}" data-id="{$link[{'id_'|cat:$type}]}">
    {$link[{'name_'|cat:$type}]|ucfirst}
    {if (!empty($link['subdata']))}
    <li class="optgroup">
        <ul class="list-unstyled">
            {include file="tinymce/loop/link-list.tpl" data=$link['subdata']}
        </ul>
    </li>
    {/if}
</li>
{/foreach}