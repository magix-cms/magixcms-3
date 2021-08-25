{$dev = $setting.mode.value === 'dev'}
{$http2 = $setting.http2.value}
{if isset($css_files) && is_array($css_files)}
    {if !$dev && !$http2}
        {assign var=css_files value=','|implode:$css_files}
        {capture name="csshref"}{'/min/?f='|cat:$css_files|cat:"{if !$dev}&amp;{$smarty.now}{/if}"}{/capture}
        {capture name="csshref"}{if $setting.concat.value}{$smarty.capture.csshref|concat_url:'css'}{else}{$smarty.capture.csshref}{/if}{/capture}
        <link rel="preload" href="{$smarty.capture.csshref}" as="style" />
        <link rel="stylesheet" href="{$smarty.capture.csshref}" />
    {else}
        {foreach $css_files as $css}
            {capture name="csshref"}{'/min/?f='|cat:$css}{/capture}
            {capture name="csshref"}{if $setting.concat.value}{$smarty.capture.csshref|concat_url:'css'}{else}{$smarty.capture.csshref}{/if}{/capture}
            <link rel="preload" href="{$smarty.capture.csshref}" as="style" />
            <link rel="stylesheet" href="{$smarty.capture.csshref}" />
        {/foreach}
    {/if}
{/if}