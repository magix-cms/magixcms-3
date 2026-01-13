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
        <div{if $classCol} class="{$classCol}"{/if} itemprop="itemListElement" itemscope itemtype="http://schema.org/Series">
            <div class="figure">
                {include file="img/img.tpl" img=$item.img lazy=$lazy}
                <div itemprop="description" class="desc">
                    <h3 itemprop="name">{$item.name}</h3>
                    <p class="text-justify">{if $truncate}
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