{if isset($data.id)}
    {$data = [$data]}
{/if}
{if !$classCol}
    {$classCol = 'col-6 col-sm-6 col-md-4'}
{/if}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div{if $classCol} class="{$classCol}{if $item@last} visible-md visible-lg{/if}{/if}">
            <h2>{$item.name|ucfirst}</h2>
            <div class="figure">
                {if $item.imgSrc.medium}
                    <img class="img-responsive" src="{$item.imgSrc.medium}" alt="{$item.name|ucfirst}" />
                {else}
                    <img class="img-responsive" src="{$item.imgSrc.default}" alt="{$item.name|ucfirst}" />
                {/if}
                <div class="caption">
                    <p class="btn btn-box btn-invert-white">Plus d'infos</p>
                </div>
                <div class="desc{if $viewport === 'mobile'} sr-only{/if}">
                    {if $item.content}
                        <p>{$item.content|strip_tags|truncate:100:'...'}</p>
                    {/if}
                </div>
                <a class="all-hover" href="{$item.url}" title="{$item.name|ucfirst}">{$item.name|ucfirst}</a>
            </div>
        </div>
    {/foreach}
{/if}