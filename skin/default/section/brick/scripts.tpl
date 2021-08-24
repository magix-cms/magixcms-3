{$dev = $setting.mode.value === 'dev'}
{$http2 = $setting.http2.value}
{strip}{capture name="vendors"}
    /skin/{$theme}/js/vendor/bootstrap-native.min.js,
    /skin/{$theme}/js/vendor/{if $dev}src/{/if}simpleLightbox{if !$dev}.min{/if}.js,
    /skin/{$theme}/js/vendor/{if $dev}src/{/if}tiny-slider{if !$dev}.min{/if}.js,
    /skin/{$theme}/js/{if $dev}src/{/if}polyfill{if !$dev}.min{/if}.js,
    {if $touch}/skin/{$theme}/js/{if $dev}src/{/if}viewport{if !$dev}.min{/if}.js,{/if}
    /skin/{$theme}/js/{if $dev}src/{/if}affixhead{if !$dev}.min{/if}.js,
    /skin/{$theme}/js/{if $dev}src/{/if}global{if !$dev}.min{/if}.js
{/capture}{/strip}
{if !$dev && !$http2}
    {capture name="vendors"}{'/min/?f='|cat:$smarty.capture.vendors|cat:"{if !$dev}&amp;{$smarty.now}{/if}"}{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.vendors|concat_url:'js'}{else}{$smarty.capture.vendors}{/if}" defer></script>
{else}
    {assign var=vendors value=','|explode:$smarty.capture.vendors}
    {foreach $vendors as $script}
        {capture name="script"}{'/min/?f='|cat:$script}{/capture}
        <script src="{if $setting.concat.value}{$smarty.capture.script|concat_url:'js'}{else}{$smarty.capture.script}{/if}" defer></script>
    {/foreach}
{/if}