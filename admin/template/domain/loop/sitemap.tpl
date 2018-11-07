{if isset($xmlItems)}
    {$data = $xmlItems}
{/if}
{if isset($data) && !empty($data)}
    <ul class="list-unstyled">
        {foreach $data as $key}
            <li><span class="fas fa-link"></span> <a class="targetblank" href="{$key}">{$key}</a></li>
        {/foreach}
    </ul>
{/if}