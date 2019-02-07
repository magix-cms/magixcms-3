{strip}
    {if isset($data.id)}
        {$data = [$data]}
    {/if}
    {if !isset($truncate)}
        {$truncate = 150}
    {/if}
    {if !isset($lazy)}
        {$lazy = true}
    {/if}
{/strip}
{if is_array($data) && !empty($data)}
    {foreach $data as $item}
        <div{if $classCol} class="{$classCol}{/if}" itemprop="hasPart" itemscope itemtype="http://schema.org/Series">
            <div class="figure">
                {if isset($item.img.name)}{$src = $item.img.medium.src}{else}{$src = $item.img.default}{/if}
                <img class="img-responsive{if $lazy} lazyload{/if}" {if $lazy}data-{/if}src="{$src}" alt="{$item.img.alt}" title="{$item.img.title}"{if count($item.img) > 1} itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium.w}" height="{$item.img.medium.h}"{/if}{/if}/>
                <div itemprop="description" class="desc">
                    <h2 itemprop="name">{$item.name}</h2>
                    <p>{if $truncate}
                            {$item.resume|truncate:$truncate:'&hellip;'}
                        {else}
                            {$item.resume}
                        {/if}</p>
                </div>
                <a class="all-hover" href="{$item.url}" title="{$item.seo.description}" itemprop="url">{$item.seo.title}</a>
            </div>
        </div>
    {/foreach}
{/if}