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
        <div{if $classCol} class="{$classCol}{/if}" itemprop="itemListElement" itemscope itemtype="http://schema.org/Product">
            <div class="figure">
                {if isset($item.img.name)}{$src = $item.img.medium.src}{else}{$src = $item.img.default}{/if}
                {strip}<picture>
                    {if isset($item.img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source type="image/webp" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src_webp']} {$item.img.medium['w']}w">
                    <source type="{$item.img.medium.ext}" sizes="{$item.img.medium['w']}px" srcset="{$item.img.medium['src']} {$item.img.medium['w']}w">
                    <!--[if IE 9]></video><![endif]-->{/if}
                    <img {if $lazy}data-{/if}src="{$src}" itemprop="image"{if $item.img.medium.crop === 'adaptative'} width="{$item.img.medium['w']}" height="{$item.img.medium['h']}"{/if} alt="{$item.img.alt}" title="{$item.img.title}" class="img-responsive{if $lazy} lazyload{/if}" />
                    </picture>{/strip}
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