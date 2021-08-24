{strip}{if !isset($size)}{$size = 'small'}{/if}
{$now = $smarty.now}
{if isset($img.name)}
    {$url = {$img['small']['src']}}
    {$urlset = "{$img['medium']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['medium']['w']}w,{$img['small']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['small']['w']}w"}
{else}
    {$url = $img.default.src}
    {$urlset = "{$img.default.src}{if $setting.mode.value === 'dev'}?{$now}{/if}"}
{/if}
{if $lazy}{$prefix = 'data-'}{else}{$prefix = ''}{/if}
{$sizes = $prefix|cat:'sizes'}
{$src = $prefix|cat:'src'}
{$srcset = $prefix|cat:'srcset'}
<picture>
    {if isset($img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
    {if isset($img[$size]['src_webp'])}
        <source type="image/webp"
        {$sizes}="(min-width: {$img['small']['w']}px) {$img['medium']['w']}px"
        {$srcset}="{$img['medium']['src_webp']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['medium']['w']}w">
        <source type="image/webp"
        {$sizes}="{$img['small']['w']}px"
        {$srcset}="{$img['small']['src_webp']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['small']['w']}w">
    {/if}
    <source type="{$img.medium.ext}"
        {$sizes}="(min-width: {$img['small']['w']}px) {$img['medium']['w']}px"
        {$srcset}="{$img['medium']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['medium']['w']}w">
    <source type="{$img.small.ext}"
        {$sizes}="{$img['small']['w']}px"
        {$srcset}="{$img['small']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['small']['w']}w">
    <!--[if IE 9]></video><![endif]-->{/if}
    <img {$src}="{$url}{if $setting.mode.value === 'dev'}?{$now}{/if}"
         {$srcset}="{$urlset}"
         {if isset($img.name)}sizes="(min-width: {$img['small']['w']}px) {$img['medium']['w']}px, {$img['small']['w']}px"{/if}
         itemprop="image"
         {if isset($img.name) && $img['medium']['crop'] === 'adaptive'}width="{$img['medium']['w']}" height="{$img['medium']['h']}"
         {elseif !isset($img.name)}width="{$img['default']['w']}" height="{$img['default']['h']}" {/if}
         alt="{$img.alt}"
         title="{$img.title}"
         class="img-responsive{if $lazy}{if $lazyClass} {$lazyClass}{else} lazyload{/if}{/if}"
         {if $lazy}loading="lazy"{/if}/>
</picture>
{/strip}