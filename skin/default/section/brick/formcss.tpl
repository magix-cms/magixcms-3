{capture name="formcss"}/skin/{$theme}/css/form{if $setting.mode.value !== 'dev'}.min{/if}.css{/capture}
{$formcsspath = $smarty.capture.formcss}
{if $setting.mode.value !== 'dev'}{$formcsspath = {'/min/?f='|cat:$formcsspath}}{else}{$formcsspath = {$url|cat:$formcsspath}}{/if}
{if $setting.concat.value}{$formcsspath = {$formcsspath|concat_url:'css'}}{/if}
<link rel="preload" href="{$formcsspath}" as="style">
<link rel="stylesheet" href="{$formcsspath}">