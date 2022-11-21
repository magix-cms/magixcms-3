{strip}{if !isset($size)}{$size = 'medium'}{/if}
    {$now = $smarty.now}
    {if isset($img.name)}
        {$url = {$img['small']['src']}}
        {$urlset = "{if $size === 'large'}{$img['large']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['large']['w']}w,{/if}{if $size !== 'small'}{$img['medium']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['medium']['w']}w,{/if}{$img['small']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['small']['w']}w"}
    {else}
        {$url = $img.default.src}
        {$urlset = "{$img.default.src}{if $setting.mode.value === 'dev'}?{$now}{/if}"}
    {/if}
    {if $lazy && in_array($browser,['Safari','Opera'])}{$prefix = 'data-'}{else}{$prefix = ''}{/if}
    {$sizes = $prefix|cat:'sizes'}
    {$src = $prefix|cat:'src'}
    {$srcset = $prefix|cat:'srcset'}
    <picture>
        {if isset($img.name)}<!--[if IE 9]><video style="display: none;"><![endif]-->
        {if isset($img[$size]['src_webp'])}
            {if $size === 'large'}
                <source type="image/webp"
                {$sizes}="(min-width: {$img['medium']['w']}px) {$img['large']['w']}px"
                {$srcset}="{$img['large']['src_webp']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['large']['w']}w">
            {/if}
            {if $size !== 'small'}
                <source type="image/webp"
                {$sizes}="(min-width: {$img['small']['w']}px) {$img['medium']['w']}px"
                {$srcset}="{$img['medium']['src_webp']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['medium']['w']}w">
            {/if}
            <source type="image/webp"
            {$sizes}="{$img['small']['w']}px"
            {$srcset}="{$img['small']['src_webp']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['small']['w']}w">
        {/if}
        {if $size === 'large'}
            <source type="{$img.large.ext}"
            {$sizes}="(min-width: {$img['medium']['w']}px) {$img['large']['w']}px"
            {$srcset}="{$img['large']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['large']['w']}w">
        {/if}
        {if $size !== 'small'}
            <source type="{$img.medium.ext}"
            {$sizes}="(min-width: {$img['small']['w']}px) {$img['medium']['w']}px"
            {$srcset}="{$img['medium']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['medium']['w']}w">
        {/if}
        <source type="{$img.small.ext}"
        {$sizes}="{$img['small']['w']}px"
        {$srcset}="{$img['small']['src']}{if $setting.mode.value === 'dev'}?{$now}{/if} {$img['small']['w']}w">
        <!--[if IE 9]></video><![endif]-->{/if}
        <img {$src}="{$url}{if $setting.mode.value === 'dev'}?{$now}{/if}"
        {$srcset}="{$urlset}"
        {if isset($img.name)}sizes="{if $size === 'large'}(min-width: {$img['medium']['w']}px) {$img['large']['w']}px,{/if}  {if $size !== 'small'}(min-width: {$img['small']['w']}px) {$img['medium']['w']}px,{/if}{$img['small']['w']}px"{/if}
        itemprop="image"
        {if isset($img.name) && $img[$size]['crop'] === 'adaptive'}width="{$img[$size]['w']}" height="{$img[$size]['h']}"
        {elseif !isset($img.name)}width="{$img['default']['w']}" height="{$img['default']['h']}" {/if}
        alt="{$img.alt}"
        title="{$img.title}"
        class="img-responsive{if $lazy && in_array($browser,['Safari','Opera'])}{if $lazyClass} {$lazyClass}{else} lazyload{/if}{/if}"
        {if $lazy}loading="lazy"{/if}/>
    </picture>
{/strip}