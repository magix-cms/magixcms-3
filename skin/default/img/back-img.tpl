{strip}
    {if !isset($size)}{$size = 'medium'}{/if}
    {if !isset($fixed)}{$fixed = true}{/if}
    {if !isset($lazy)}{$lazy = true}{/if}
    {if !isset($responsiveC)}{$responsiveC = true}{/if}
    {$now = $smarty.now}
    {if !isset($img.name)}{$img = $img.default}{/if}
    {if $lazy && in_array($browser,['Safari'])}{$prefix = 'data-'}{else}{$prefix = ''}{/if}
    {$sizes = $prefix|cat:'sizes'}
    {$src = $prefix|cat:'src'}
    {$srcset = $prefix|cat:'srcset'}
    {if in_array($size,['small','medium','large'])}
        {$url = {$img['small']['src']}}
        {$urlset = "{if $size === 'large'}{$img['large']['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['large']['w']}w,{/if}{if $size !== 'small'}{$img['medium']['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['medium']['w']}w,{/if}{$img['small']['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['small']['w']}w"}
        <picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            {if isset($img[$size]['src_webp'])}
                {if $size === 'large'}
                    <source type="image/webp"
                    media="(min-width: {$img['medium']['w']}px)"
                    {$sizes}="{$img['large']['w']}px"
                    {$srcset}="{$img['large']['src_webp']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['large']['w']}w">
                {/if}
                {if $size !== 'small'}
                    <source type="image/webp"
                    media="(min-width: {$img['small']['w']}px)"
                    {$sizes}="{$img['medium']['w']}px"
                    {$srcset}="{$img['medium']['src_webp']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['medium']['w']}w">
                {/if}
                <source type="image/webp"
                {$sizes}="{$img['small']['w']}px"
                {$srcset}="{$img['small']['src_webp']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['small']['w']}w">
            {/if}
            {if $size === 'large'}
                <source type="{$img.large.ext}"
                media="(min-width: {$img['medium']['w']}px)"
                {$sizes}="{$img['large']['w']}px"
                {$srcset}="{$img['large']['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['large']['w']}w">
            {/if}
            {if $size !== 'small'}
                <source type="{$img.medium.ext}"
                media="(min-width: {$img['small']['w']}px)"
                {$sizes}="{$img['medium']['w']}px"
                {$srcset}="{$img['medium']['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['medium']['w']}w">
            {/if}
            <source type="{$img.small.ext}"
            {$sizes}="{$img['small']['w']}px"
            {$srcset}="{$img['small']['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img['small']['w']}w">
            <!--[if IE 9]></video><![endif]-->
            <img {$src}="{$url}{if $setting.mode === 'dev'}?{$now}{/if}"
            {$srcset}="{$urlset}"
            sizes="{if $size === 'large'}(min-width: {$img['medium']['w']}px) {$img['large']['w']}px,{/if}  {if $size !== 'small'}(min-width: {$img['small']['w']}px) {$img['medium']['w']}px,{/if}{$img['small']['w']}px"
            itemprop="image"
            {*{if $img[$size]['crop'] === 'adaptive'}width="{$img[$size]['w']}" height="{$img[$size]['h']}"
            {elseif !isset($img.name)}width="{$img['default']['w']}" height="{$img['default']['h']}" {/if}*}
            width="{$img.default[$size]['w']}" height="{$img.default[$size]['h']}"
            alt="{$img.alt}"
            title="{$img.title}"
            class="{if $responsiveC}img-responsive{/if}{if $lazy && in_array($browser,['Safari','Opera'])}{if $lazyClass} {$lazyClass}{else} lazyload{/if}{/if}"
            {if $lazy}loading="lazy"{/if}/>
        </picture>
    {elseif is_array($size)}
        {$urlset = []}
        {foreach $size as $k => $sz}
            {$urlset[] = "{$img[$sz]['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img[$sz]['w']}w"}
            {if $sz@last}{$url = {$img[$sz]['src']}}{/if}
        {/foreach}
        {$urlset = implode(',',$urlset)}
        <picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            {foreach $size as $k => $sz}
                {if isset($img[$sz]['src_webp'])}
                    <source type="image/webp"
                    {if !$sz@last}
                        {$next = $size[$k+1]}
                        media="(min-width: {$img[$next]['w']}px)"
                    {/if}
                    {$sizes}="{$img[$sz]['w']}px"
                    {$srcset}="{$img[$sz]['src_webp']}{if $setting.mode === 'dev'}?{$now}{/if} {$img[$sz]['w']}w">
                {/if}
            {/foreach}
            {$sizes_list = ''}
            {foreach $size as $k => $sz}
                {if isset($img[$sz])}
                    <source type="{$img[$sz].ext}"
                    {if !$sz@last}
                        {$next = $size[$k+1]}
                        media="(min-width: {$img[$next]['w']}px)"
                        {$sizes_list = $sizes_list|cat:"(min-width: {$img[$next]['w']}px) {$img[$sz]['w']}px,"}
                    {else}
                        {$sizes_list = $sizes_list|cat:"{$img[$sz]['w']}px"}
                        {$size = $sz}
                    {/if}
                    {$sizes}="{$img[$sz]['w']}px"
                    {$srcset}="{$img[$sz]['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img[$sz]['w']}w">
                {/if}
            {/foreach}
            <!--[if IE 9]></video><![endif]-->
            <img {$src}="{$url}{if $setting.mode === 'dev'}?{$now}{/if}"
            {$sizes}="{$sizes_list}"
            {$srcset}="{$urlset}"
            itemprop="image"
            {*{if $fixed}{if $img[$size]['crop'] === 'adaptive'}width="{$img[$size]['w']}" height="{$img[$size]['h']}"
            {elseif !isset($img.name)}width="{$img['default']['w']}" height="{$img['default']['h']}" {/if}{/if}*}
            width="{$img.default[$size]['w']}" height="{$img.default[$size]['h']}"
            alt="{$img.alt}"
            title="{$img.title}"
            class="{if $responsiveC}img-responsive{/if}{if $lazy && in_array($browser,['Safari','Opera'])}{if $lazyClass} {$lazyClass}{else} lazyload{/if}{/if}"
            {if $lazy}loading="lazy"{/if}/>
        </picture>
    {/if}
{/strip}