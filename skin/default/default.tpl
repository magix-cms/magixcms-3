{$main_color='#3b61a9'}
{if $browser !== 'IE'}<link rel="preload" href="/skin/{$theme}/css/default{if $setting.mode.value !== 'dev'}.min{/if}.css" as="style">{/if}
<link rel="stylesheet" href="/skin/{$theme}/css/default{if $setting.mode.value !== 'dev'}.min{/if}.css">