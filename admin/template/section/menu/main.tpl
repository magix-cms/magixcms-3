<li {if isset($class)} class="{$class}"{/if}>
    <a href="{$url}/{baseadmin}/index.php?controller=dashboard">
        <span class="fa fa-tachometer"></span> Tableau de bord
    </a>
</li>
{if $setting['mode'] === 'dev'}
<li {if isset($class)} class="{$class}"{/if}>
    <a href="{$url}/{baseadmin}/index.php?controller=setting&tab=advanced" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{#mode_dev_info#}">
        <span class="text-info"><i class="fas fa-info-circle"></i> {#mode_setting#}: {#mode_dev#}</span>
    </a>
</li>
{/if}
{if $setting['maintenance'] === '1'}
<li {if isset($class)} class="{$class}"{/if}>
    <a href="{$url}/{baseadmin}/index.php?controller=setting&tab=advanced" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{#maintenance_alert#}">
        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> {#maintenance_setting#}</span>
    </a>
</li>
{/if}
{*<li {if isset($class)} class="{$class}"{/if}>
    <a href="#">
        <span class="fa fa-pie-chart"></span> Statistiques
    </a>
</li>*}