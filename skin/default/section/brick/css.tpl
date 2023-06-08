{$dev = $setting.mode === 'dev'}
{$http2 = $setting.http2}
{if isset($css_files) && is_array($css_files)}
    {css_links css_files=$css_files dev=$dev theme=$theme}
    {if !$dev && !$http2}
        {assign var=css_files value=implode($css_files,',')}
        {capture name="csshref"}{'/min/?f='|cat:$css_files|cat:"{if !$dev}&amp;{$smarty.now}{/if}"}{/capture}
        {capture name="csshref"}{if $setting.concat}{$smarty.capture.csshref|concat_url:'css'}{else}{$smarty.capture.csshref}{/if}{/capture}
        <link rel="preload" href="{$smarty.capture.csshref}" as="style" />
        <link rel="stylesheet" href="{$smarty.capture.csshref}" />
    {else}
        {foreach $css_files as $css}
            {capture name="csshref"}{if !$dev}{'/min/?f='|cat:$css}{else}{$css}{/if}{/capture}
            {capture name="csshref"}{if $setting.concat}{$smarty.capture.csshref|concat_url:'css'}{else}{$smarty.capture.csshref}{/if}{/capture}
            <link rel="preload" href="{$smarty.capture.csshref}" as="style" />
            <link rel="stylesheet" href="{$smarty.capture.csshref}" />
        {/foreach}
    {/if}
{/if}