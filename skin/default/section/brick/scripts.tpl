{$dev = $setting.mode.value === 'dev'}
{$http2 = $setting.http2.value}
{if $jquery}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
{/if}
{if isset($js_files) && is_array($js_files)}
    {foreach $js_files as $loading => $files}
        {if !$dev && !$http2}
            {assign var=files value=','|implode:$files}
            {capture name="jssrc"}{"/min/?{if $loading eq 'group'}g{else}f{/if}="|cat:$files|cat:"{if !$dev}&amp;{$smarty.now}{/if}"}{/capture}
            {capture name="jssrc"}{if $setting.concat.value}{$smarty.capture.jssrc|concat_url:'js'}{else}{$smarty.capture.jssrc}{/if}{/capture}
            <script src="{$smarty.capture.jssrc}"{if $loading eq 'async' || $loading eq 'defer'} {$loading}{/if}></script>
        {else}
            {foreach $files as $js}
                {capture name="jssrc"}{"/min/?{if $loading eq 'group'}g{else}f{/if}="|cat:$js}{/capture}
                {capture name="jssrc"}{if $setting.concat.value}{$smarty.capture.jssrc|concat_url:'js'}{else}{$smarty.capture.jssrc}{/if}{/capture}
                <script src="{$smarty.capture.jssrc}"{if $loading eq 'async' || $loading eq 'defer'} {$loading}{/if}></script>
            {/foreach}
        {/if}
    {/foreach}
{/if}