{$dev = $setting.mode.value === 'dev'}
{strip}{capture name="vendors"}
    /skin/{$theme}/js/vendor/bootstrap-native.min.js,
    /skin/{$theme}/js/vendor/{if $dev}src/{/if}simpleLightbox{if !$dev}.min{/if}.js,
    /skin/{$theme}/js/vendor/{if $dev}src/{/if}tiny-slider{if !$dev}.min{/if}.js,
    /skin/{$theme}/js/{if $dev}src/{/if}polyfill{if !$dev}.min{/if}.js,
    {if $viewport !== 'mobile'}/skin/{$theme}/js/{if $dev}src/{/if}affixhead{if !$dev}.min{/if}.js,{/if}
    /skin/{$theme}/js/{if $dev}src/{/if}global{if !$dev}.min{/if}.js
{/capture}{/strip}
{if !$dev}
    {capture name="vendors"}{'/min/?f='|cat:$smarty.capture.vendors}{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.vendors|concat_url:'js'}{else}{$smarty.capture.vendors}{/if}" defer></script>
{else}
    {assign var=vendors value=','|explode:$smarty.capture.vendors}
    {foreach $vendors as $script}
        <script src="{if $setting.concat.value}{$url|cat:$script|concat_url:'js'}{else}{$url|cat:$script}{/if}" defer></script>
    {/foreach}
{/if}