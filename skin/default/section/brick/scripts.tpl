{strip}{capture name="vendors"}
    /skin/{$theme}/js/vendor/bootstrap-custom.min.js,
    {if $touch}/skin/{$theme}/js/vendor/jquery.detect_swipe.min.js,{/if}
    /skin/{$theme}/js/vendor/featherlight.min.js,
    /skin/{$theme}/js/vendor/featherlight.gallery.min.js,
    /skin/{$theme}/js/vendor/owl.carousel.min.js,
    {if $viewport !== 'mobile'}/skin/{$theme}/js/affixhead.min.js,{/if}
    /skin/{$theme}/js/global.min.js
{/capture}{/strip}
{if $setting.mode.value !== 'dev'}
    {capture name="vendors"}{'/min/f='|cat:$smarty.capture.vendors}{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.vendors|concat_url:'js'}{else}{$smarty.capture.vendors}{/if}" defer></script>
{else}
    {assign var=vendors value=','|explode:$smarty.capture.vendors}
    {foreach $vendors as $script}
        <script src="{if $setting.concat.value}{$url|cat:$script|concat_url:'js'}{else}{$url|cat:$script}{/if}" defer></script>
    {/foreach}
{/if}