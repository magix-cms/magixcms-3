{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        {*{$item|var_dump}*}
        {if $config[$item.name_share]}
            {$icon = {$item.name_share}}
            <li class="share-{$item.name_share}">
                <a class="targetblank" href="{$item.url_share|replace:'%URL%':{$url|cat:$smarty.server.REQUEST_URI}|replace:'%NAME%':{$smarty.capture.title|urlencode}}" title="{#share_on#|ucfirst} {$item.name_share|ucfirst}">
                    <span class="fab ico ico-{$icon}"></span>
                    <span class="sr-only">{$item.name_share|ucfirst}</span>
                </a>
            </li>
        {/if}
    {/foreach}
{/if}