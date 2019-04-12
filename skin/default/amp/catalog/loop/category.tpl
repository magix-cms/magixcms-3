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
        <div{if $classCol} class="{$classCol}{/if}" itemprop="itemListElement" itemscope itemtype="http://schema.org/Series">
            <div class="figure">
                {if $item.img.medium.src}
                    <amp-img alt="{$item.img.alt}"
                             title="{$item.img.title}"
                             src="{$item.img.medium['src_webp']}"
                             width="{$item.img.medium['w']}"
                             height="{$item.img.medium['h']}"
                             layout="responsive"
                             itemprop="image">
                        <amp-img alt="{$item.img.alt}"
                                 fallback
                                 title="{$item.img.title}"
                                 src="{$item.img.medium['src']}"
                                 width="{$item.img.medium['w']}"
                                 height="{$item.img.medium['h']}"
                                 layout="responsive">
                        </amp-img>
                    </amp-img>
                {else}
                    <amp-img src="{$item.img.default}"
                             alt="{$item.name}"
                             title="{$item.name}"
                             layout="responsive"
                             width="500"
                             height="309"></amp-img>
                {/if}
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