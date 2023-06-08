{strip}
    {if !isset($css2)}{$css2 = true}{/if}
    {$api = 'https://fonts.googleapis.com/css'}
    {if $fonts && is_array($fonts)}
        {if $css2}
            {$api = $api|cat:'2'}
            {$families = ''}
            {foreach $fonts as $font}
                {$fontfamily = $font@key|replace:' ':'+'}
                {if $font}
                    {$style = 'wght@'}
                    {$wghts = ','|explode:$font}
                    {$normals = []}
                    {$italics = []}
                    {$italic = false}

                    {foreach $wghts as $wght}
                        {if strpos($wght,'italic') !== false}
                            {$italic = true}
                            {$wght = $wght|replace:'italic':''}
                            {$italics[] = $wght}
                        {else}
                            {$normals[] = $wght}
                        {/if}
                    {/foreach}

                    {if $italic}
                        {$style = 'ital,'|cat:$style}
                        {foreach $normals as $wght}
                            {$wght = '0,'|cat:$wght|cat:';'}
                            {$style = $style|cat:$wght}
                        {/foreach}
                        {foreach $italics as $wght}
                            {$wght = '1,'|cat:$wght}
                            {if !$wght@last}
                                {$wght = $wght|cat:';'}
                            {/if}
                            {$style = $style|cat:$wght}
                        {/foreach}
                    {else}
                        {foreach $normals as $wght}
                            {if !$wght@last}
                                {$wght = $wght|cat:';'}
                            {/if}
                            {$style = $style|cat:$wght}
                        {/foreach}
                    {/if}
                    {$families = $families|cat:'family='|cat:$fontfamily|cat:':'|cat:$style}
                {else}
                    {$families = $families|cat:'family='|cat:$fontfamily}
                {/if}
                {if !$font@last}
                    {$families = $families|cat:'&'}
                {/if}
            {/foreach}
        {else}
            {$families = 'family='}
            {foreach $fonts as $font}
                {$fontfamily = $font@key|replace:' ':'+'}
                {$families = $families|cat:$fontfamily}
                {if $font}
                    {$style = ':'|cat:$font}
                    {$families = $families|cat:$style}
                {/if}
                {if !$font@last}
                    {$families = $families|cat:'|'}
                {/if}
            {/foreach}
        {/if}
    {/if}
    {if $browser !== 'IE'}<link rel="preload" href="{$api}?{$families}&display=swap" as="style">{/if}
    <link rel="stylesheet" href="{$api}?{$families}&display=swap">
{/strip}