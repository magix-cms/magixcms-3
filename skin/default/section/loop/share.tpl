{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        {*{$item|var_dump}*}
        {if $config[$item.name_share]}
            {$icon = {$item.name_share}}
            {switch $item.name_share}{case 'facebook' break}{$icon = 'facebook-f'}{case 'google' break}{$icon = 'google-plus-g'}{case 'linkedin' break}{$icon = 'linkedin-in'}{case 'pinterest' break}{$icon = 'pinterest-p'}{case 'google' break}{$icon = 'google-plus-g'}{/switch}
            <li class="share-{$item.name_share}">
                <a class="targetblank" href="{$item.url_share|replace:'%URL%':{$url|cat:$smarty.server.REQUEST_URI}|replace:'%NAME%':{$smarty.capture.title|urlencode}}" title="{#share_on#|ucfirst} {$item.name_share|ucfirst}">
                    <span class="fab fa-{$icon}"></span>
                    <span class="sr-only">{$item.name_share|ucfirst}</span>
                </a>
            </li>
        {/if}
    {/foreach}
{/if}