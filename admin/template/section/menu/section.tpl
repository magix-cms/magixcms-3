{if !isset($mobile)}
    {$mobile = false}
{/if}
<ul class="nav">
    {if {employee_access type="view_access" class_name="frontend_controller_customer"} eq 1}
    <li class="has-submenu{if $smarty.server.SCRIPT_NAME == '/customer.php'} active{/if}">
        {if $mobile}<button type="button" class="navbar-toggle{if $smarty.server.SCRIPT_NAME =="/customer.php"} open{/if}" data-toggle="collapse" data-target="#menu-customer">
            <span class="fa fa-plus"></span>
        </button>{/if}
        <a href="{geturl}/customer.php">
            <span class="fa fa-users"></span> Clients
        </a>
        <ul class="nav list-unstyled{if $mobile} collapse navbar-collapse{if $smarty.server.SCRIPT_NAME =="/customer.php"} in{/if}{/if}">
            <li>
                <a href="{geturl}/customer.php">Listes des clients</a>
            </li>
            {if {employee_access type="add_access" class_name="frontend_controller_customer"} eq 1}
            <li>
                <a href="{geturl}/customer.php?action=add">Ajouter un client</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if {employee_access type="view_access" class_name="frontend_controller_defunct"} eq 1}
    <li class="has-submenu">
        <a href="{geturl}/defunct.php">
            <span class="fa fa-folder"></span> Défunts
        </a>
        <ul class="nav list-unstyled">
            <li>
                <a href="{geturl}/defunct.php">Listes des défunts</a>
            </li>
            {if {employee_access type="add_access" class_name="frontend_controller_defunct"} eq 1}
            <li>
                <a href="{geturl}/defunct.php?action=add">Ajouter un défunt</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if {employee_access type="view_access" class_name="frontend_controller_funeral"} eq 1}
    <li class="has-submenu">
        <a href="{geturl}/funeral.php">
            <span class="fa fa-home"></span> Funérailles
        </a>
        <ul class="nav list-unstyled">
            <li>
                <a href="{geturl}/funeral.php">Listes des funérailles</a>
            </li>
            {if {employee_access type="add_access" class_name="frontend_controller_funeral"} eq 1}
            <li>
                <a href="{geturl}/funeral.php?action=add">Ajouter des funérailles</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if {employee_access type="view_access" class_name="frontend_controller_document"} eq 1}
    <li>
        <a href="{geturl}/document.php">
            <span class="fa fa-file"></span> Documents
        </a>
    </li>
    {/if}
    {if {employee_access type="view_access" class_name="frontend_controller_employee"} eq 1}
    <li class="has-submenu{if $smarty.server.SCRIPT_NAME == '/employee.php'} active{/if}">
        <a href="{geturl}/employee.php">
            <span class="fa fa-user"></span> Administration
        </a>
        <ul class="nav list-unstyled">
            <li{if $smarty.server.SCRIPT_NAME == '/employee.php'} class="active"{/if}>
                <a href="{geturl}/employee.php">Listes des employés</a>
            </li>
            {if {employee_access type="add_access" class_name="frontend_controller_employee"} eq 1}
            <li{if $smarty.server.SCRIPT_NAME == '/employee.php' && $smarty.get.action == 'add'} class="active"{/if}>
                <a href="{geturl}/employee.php?action=add">Ajouter un employé</a>
            </li>
            {/if}
            {if {employee_access type="view_access" class_name="frontend_controller_access"} eq 1}
            <li{if $smarty.server.SCRIPT_NAME == '/access.php'} class="active"{/if}>
                <a href="{geturl}/access.php">Gestion des permissions</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
    {if {employee_access type="view_access" class_name="frontend_controller_staff"} eq 1}
    <li class="has-submenu{if $smarty.server.SCRIPT_NAME == '/staff.php'} active{/if}">
        <a href="{geturl}/staff.php">
            <span class="fa fa-user"></span> Personnel
        </a>
        <ul class="nav list-unstyled">
            <li{if $smarty.server.SCRIPT_NAME == '/staff.php'} class="active"{/if}>
                <a href="{geturl}/staff.php">Listes des ouvriers</a>
            </li>
            {if {employee_access type="add_access" class_name="frontend_controller_staff"} eq 1}
            <li{if $smarty.server.SCRIPT_NAME == '/staff.php' && $smarty.get.action == 'add'} class="active"{/if}>
                <a href="{geturl}/staff.php?action=add">Ajouter un ouvrier</a>
            </li>
            {/if}
        </ul>
    </li>
    {/if}
</ul>