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
    <li class="{if $smarty.get.controller == 'about'}active{/if}">
        <a href="{geturl}/{baseadmin}/index.php?controller=about">
            <span class="fa fa-briefcase"></span> {#root_about#}
        </a>
    </li>
    {if {employee_access type="view" class_name="backend_controller_home"} eq 1}
    <li class="{if $smarty.get.controller == 'home'}active{/if}">
        <a href="{geturl}/{baseadmin}/index.php?controller=home">
            <span class="fa fa-home"></span> {#root_home#}
        </a>
    </li>
    {/if}
    {if {employee_access type="view" class_name="backend_controller_pages"} eq 1}
        <li class="has-submenu{if $smarty.get.controller == 'pages'} active{/if}">
            <a href="{geturl}/{baseadmin}/index.php?controller=pages">
                <span class="fa fa-file-text-o"></span> {#root_pages#}
            </a>
            <ul class="nav list-unstyled">
                <li{if $smarty.get.controller == 'pages'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=pages">Listes des pages</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_pages"} eq 1}
                    <li{if $smarty.get.controller == 'employee' && $smarty.get.action == 'add'} class="active"{/if}>
                        <a href="{geturl}/{baseadmin}/index.php?controller=pages&action=add">Ajouter une page</a>
                    </li>
                {/if}
            </ul>
        </li>
    {/if}
    <li class="{if $smarty.get.controller == 'catalog'} active{/if}">
        <a href="#">
            <span class="fa fa-shopping-cart"></span> {#root_catalog#}
        </a>
    </li>
    <li class="{if $smarty.get.controller == 'plugins'}active{/if}">
        <a href="{geturl}/{baseadmin}/index.php?controller=plugins">
            <span class="fa fa-cogs"></span> Extensions
        </a>
        {if is_array($getItemsPlugins) && !empty($getItemsPlugins)}
        <ul class="nav list-unstyled">
            {foreach $getItemsPlugins as $item}
            <li class="{if $smarty.get.controller == {$item.name}}active{/if}">
                <a href="{geturl}/{baseadmin}/index.php?controller={$item.name}">
                    <span class="fa {if $smarty.get.controller == {$item.name}}fa-angle-right{else}fa-angle-down{/if}"></span> {$item.name}
                </a>
            </li>
            {/foreach}
        </ul>
        {/if}
    </li>
</ul>