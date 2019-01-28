{strip}
    {if isset($data.id)}
        {$data = [$data]}
    {/if}
    {if !isset($truncate)}
        {$truncate = 150}
    {/if}
{/strip}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div{if $classCol} class="{$classCol}{/if}" itemprop="hasPart" itemscope itemtype="http://schema.org/Series">
            <div class="figure">
                {if count($item.img) > 1}
                    <img class="img-responsive lazyload" src="{$item.img.default}" data-src="{$item.img.medium.src}" alt="{$item.name}" title="{$item.name}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}/>
                {else}
                    <img class="img-responsive" src="{$item.img.default}" alt="{$item.name}" title="{$item.name}" />
                {/if}
                <div itemprop="description" class="desc">
                    <h2 itemprop="name">{$item.name|ucfirst}</h2>
                    {if $item.resume}
                        <p>{$item.resume|truncate:$truncate:'...'}</p>
                    {elseif $item.content}
                        <p>{$item.content|strip_tags|truncate:$truncate:'...'}</p>
                    {/if}
                </div>
                <a class="all-hover" href="{$item.url}" title="{$item.name|ucfirst}" itemprop="url">{$item.name|ucfirst}</a>
            </div>
        </div>
    {/foreach}
{/if}