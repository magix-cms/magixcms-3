{if isset($data.id)}
    {$data = [$data]}
{/if}
{if !$classCol}
    {$classCol = 'col-xs-12 col-sm-6 col-md-12'}
{/if}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div class="news-box">
            <div class="time-published">
                <p class="tday">{$item.date.publish|date_format:"%e"}</p>
                <p>{$item.date.publish|date_format:"%m %y"|replace:' ':'&thinsp;/&thinsp;'}</p>
            </div>
            <div class="resume">
                <h4>{$item.name|ucfirst}</h4>
                {if $item.content}<p>{$item.content|strip_tags|truncate:250:'&hellip;'}</p>{/if}
            </div>
            <a href="{$item.uri}" title="{#read_more#|ucfirst} {$item.name|ucfirst}" class="all-hover"><span class="sr-only">{$item.name|ucfirst}</span></a>
        </div>
    {/foreach}
{/if}