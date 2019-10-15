{if !isset($size)}{$size = 'medium'}{/if}
{if isset($img.name)}{$src = $img[$size]['src']}{else}{$src = $img.default}{/if}
{$now = $smarty.now}
{strip}<picture>
    {if isset($img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
    <source type="image/webp" sizes="{$img[$size]['w']}px" srcset="{$img[$size]['src_webp']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img[$size]['w']}w">
    <source type="{$img.medium.ext}" sizes="{$img[$size]['w']}px" srcset="{$img[$size]['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img[$size]['w']}w">
    <!--[if IE 9]></video><![endif]-->{/if}
    <img {if $lazy}data-{/if}src="{$src}{if $setting.mode.value === 'dev'}?{$now}{/if}" itemprop="image" {if isset($img.name) && $img[$size]['crop'] === 'adaptative'}width="{$img[$size]['w']}" height="{$img[$size]['h']}" {/if}alt="{$img.alt}" title="{$img.title}" class="img-responsive{if $lazy} lazyload{/if}{if $imgClass} {$imgClass}{/if}" />
    </picture>{/strip}