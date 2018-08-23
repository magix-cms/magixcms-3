{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        {if $config[$item.name_share]}
        <li class="share-{$item.name_share}">
            <a class="targetblank" href="{$item.url_share|replace:'%URL%':{$url|cat:$smarty.server.REQUEST_URI}|replace:'%NAME%':{$smarty.capture.title|urlencode}}" title="{#share_on#|ucfirst} {$item.name_share|ucfirst}">
                <span class="fa fa-{$item.name_share}{if $item.name_share == 'google'}-plus{/if}"></span>
                <span class="sr-only">{$item.name_share|ucfirst}</span>
            </a>
        </li>
        {/if}
    {/foreach}
{/if}