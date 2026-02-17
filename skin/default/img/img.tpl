{strip}
    {if !isset($size)}{$size = 'medium'}{/if}
    {if !isset($fixed)}{$fixed = true}{/if}
    {if !isset($lazy)}{$lazy = true}{/if}
    {if !isset($responsiveC)}{$responsiveC = true}{/if}

    {$now = $smarty.now}
    {if !isset($img.name)}{$img = $img.default}{/if}

    {if $lazy && in_array($browser,['Safari'])}{$prefix = 'data-'}{else}{$prefix = ''}{/if}
    {$sizes_attr = $prefix|cat:'sizes'}
    {$src_attr = $prefix|cat:'src'}
    {$srcset_attr = $prefix|cat:'srcset'}

    {if is_array($size)}
        {$count = $size|count}
        {$idx = $count - 1}
        {$target_key = $size[$idx]}
    {else}
        {$target_key = $size}
    {/if}

    {if isset($img[$target_key])}
        {$visual_node = $img[$target_key]}
    {else}
        {$first = reset($img)}
        {$visual_node = (is_array($first) && isset($first.w)) ? $first : $img.default}
    {/if}


    {$available_sizes = []}
    {foreach $img as $k => $v}
        {if is_array($v) && isset($v.w) && isset($v.src)}
            {if $v.w <= $visual_node.w}
                {$available_sizes[$v.w] = $k}
            {/if}
        {/if}
    {/foreach}

    {$null = krsort($available_sizes)}
    {$sorted_keys = array_values($available_sizes)}

    {if isset($img['large']['src'])}
        {$url = $img['large']['src']}
    {elseif isset($img.large.src)}
        {$url = $img.large.src}
    {else}
        {$all_sizes = []}
        {foreach $img as $k => $v}
            {if is_array($v) && isset($v.w)}{$all_sizes[$v.w] = $k}{/if}
        {/foreach}
        {$null = krsort($all_sizes)}
        {$largest_key = reset($all_sizes)}
        {$url = $img[$largest_key]['src']}
    {/if}

    {$urlset_arr = []}
    {$sizes_parts = []}

    {foreach $sorted_keys as $k => $sz}
        {$urlset_arr[] = "{$img[$sz]['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img[$sz]['w']}w"}

        {if $k == 0}
            {$sizes_parts[] = "(min-width: {$img[$sz]['w']}px) {$img[$sz]['w']}px"}
        {else}
            {$sizes_parts[] = "{$img[$sz]['w']}px"}
        {/if}
    {/foreach}

    {$urlset_string = implode(', ', $urlset_arr)}
    {$sizes_string = implode(', ', $sizes_parts)}

    <picture>
        {foreach $sorted_keys as $k => $sz}
            {if isset($img[$sz]['src_webp'])}
                <source type="image/webp"
                        media="(min-width: {$img[$sz]['w']}px)"
                {$sizes_attr}="{$img[$sz]['w']}px"
                {$srcset_attr}="{$img[$sz]['src_webp']}{if $setting.mode === 'dev'}?{$now}{/if} {$img[$sz]['w']}w">
            {/if}
        {/foreach}

        {foreach $sorted_keys as $k => $sz}
            <source type="{$img[$sz].ext}"
                    media="(min-width: {$img[$sz]['w']}px)"
            {$sizes_attr}="{$img[$sz]['w']}px"
            {$srcset_attr}="{$img[$sz]['src']}{if $setting.mode === 'dev'}?{$now}{/if} {$img[$sz]['w']}w">
        {/foreach}

        <img {$src_attr}="{$visual_node.src}{if $setting.mode === 'dev'}?{$now}{/if}"
        {$sizes_attr}="{$sizes_string}"
        {$srcset_attr}="{$urlset_string}"
        itemprop="image"
        width="{$visual_node.w}"
        height="{$visual_node.h}"
        alt="{$img.alt}"
        title="{$img.title}"
        class="{if $responsiveC}img-responsive{/if}{if $lazy && in_array($browser,['Safari','Opera'])}{if $lazyClass} {$lazyClass}{else} lazyload{/if}{/if}"
        {if $lazy}loading="lazy"{/if}/>
    </picture>
{/strip}