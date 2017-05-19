{if !isset($mobile)}
    {$mobile = false}
{/if}
<ul class="nav">
    {if {employee_access type="view" class_name="backend_controller_employee"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'employee'} active{/if}">
        <a href="{geturl}/{baseadmin}/index.php?controller=employee">
            <span class="fa fa-user"></span> Administration
        </a>
        <ul class="nav list-unstyled">
            <li{if $smarty.get.controller == 'employee'} class="active"{/if}>
                <a href="{geturl}/{baseadmin}/index.php?controller=employee">Listes des employés</a>
            </li>
            {if {employee_access type="append" class_name="backend_controller_employee"} eq 1}
            <li{if $smarty.get.controller == 'employee' && $smarty.get.action == 'add'} class="active"{/if}>
                <a href="{geturl}/{baseadmin}/index.php?controller=employee&action=add">Ajouter un employé</a>
            </li>
            {/if}
            {if {employee_access type="view" class_name="backend_controller_access"} eq 1}
            <li{if $smarty.get.controller == 'access'} class="active"{/if}>
                <a href="{geturl}/{baseadmin}/index.php?controller=access">Gestion des permissions</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    <li class="has-submenu{if $smarty.get.controller == 'plugins'} active{/if}">
        <a href="{geturl}/{baseadmin}/index.php?controller=plugins">
            <span class="fa fa-cogs"></span> Extensions
        </a>
        {if is_array($getItemsPlugins) && !empty($getItemsPlugins)}
        <ul class="nav list-unstyled">
            {foreach $getItemsPlugins as $item}
            <li>
                <a href="{geturl}/{baseadmin}/index.php?controller={$item.name}">{$item.name}</a>
            </li>
            {/foreach}
        </ul>
        {/if}
    </li>
</ul>