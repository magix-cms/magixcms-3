{if isset($data.id)}
    {$data = [$data]}
{/if}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div class="news-footer col-4 col-xs-3 col-sm-4 col-md-5 col-lg-12">
            <p class="h5">{$item.name|ucfirst}</p>
            {if $item.resume || $item.content}
            <p>{if $item.resume}{$item.resume|truncate:100:'...'}{elseif $item.content}{$item.content|strip_tags|truncate:100:"..."}{/if}</p>
            {/if}
            <small><time class="date" datetime="{$item.date.publish}">{$item.date.publish.timestamp|date_format:"%e %B %Y"}</time></small>
            <a class="all-hover" href="{$item.url}" title="{$item.seo.description}" itemprop="url">{$item.seo.title}</a>
        </div>
    {/foreach}
{/if}