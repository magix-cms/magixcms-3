{strip}{if $fonts && is_array($fonts)}
    {$family = ''}
    {foreach $fonts as $font}
        {$fontfamily = $font@key|replace:' ':'+'}
        {if $font}
            {$style = ':'|cat:$font}
        {else}
            {$style = ''}
        {/if}
        {$family = $family|cat:$fontfamily|cat:$style}
        {if !$font@last}
            {$family = $family|cat:'|'}
        {/if}
    {/foreach}
    {if $browser !== 'IE'}<link rel="preload" href="https://fonts.googleapis.com/css?family={$family}" as="style">{/if}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family={$family}">
{/if}{/strip}