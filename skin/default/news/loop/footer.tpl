{if isset($data.id)}
    {$data = [$data]}
{/if}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div class="news-footer col-12 col-xs-6 col-sm-12 col-lg-6">
            <p class="h5">{$item.name|ucfirst}</p>
            {if $item.resume || $item.content}
            <p>{if $item.resume}{$item.resume|truncate:100:'...'}{elseif $item.content}{$item.content|strip_tags|truncate:100:"..."}{/if}</p>
            {/if}
            <small><time class="date" datetime="{$item.date.publish}">{$item.date.publish.timestamp|date_format:"%e %B %Y"}</time></small>
            <a class="all-hover" href="{$item.url}" title="{$item.seo.description}" itemprop="url">{$item.seo.title}</a>
        </div>
    {/foreach}
{/if}