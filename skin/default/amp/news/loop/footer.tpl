{if isset($data.id)}
    {$data = [$data]}
{/if}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <a href="{$item.url}" title="{$item.name|ucfirst}">
        <div class="media-footer">
            <h5>{$item.name|ucfirst}</h5>
            {if $item.resume || $item.content}
            <p>{if $item.resume}{$item.resume|truncate:100:'...'}{elseif $item.content}{$item.content|strip_tags|truncate:100:"..."}{/if}</p>
            {/if}
            <small><time class="date" datetime="{$item.date.publish}">{$item.date.publish|date_format:"%e %B %Y"}</time></small>
        </div>
        </a>
    {/foreach}
{/if}