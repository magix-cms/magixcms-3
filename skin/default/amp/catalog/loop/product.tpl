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
                {if $item.imgSrc.medium}
                    <amp-img src="{$item.imgSrc.large}"
                             alt="{$item.name}"
                             title="{$item.name}"
                             layout="responsive"
                             width="500"
                             height="309" itemprop="image"></amp-img>
                {else}
                    <amp-img src="{$item.imgSrc.default}"
                             alt="{$item.name}"
                             title="{$item.name}"
                             layout="responsive"
                             width="500"
                             height="309"></amp-img>
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