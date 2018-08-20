<ul class="nav navbar-nav">
    {*<li>
        <a href="#">
            <span class="fa fa-user"></span> {$employeeData.pseudo_admin|ucfirst}
        </a>
    </li>*}
    <li>
        <a href="{$url}" title="Aller sur le site" class="targetblank">
            <span class="hidden-sm hidden-md hidden-lg"><span class="fa fa-arrow-right"></span></span> <span class="fa fa-desktop"></span><span class="hidden-ph hidden-xs"> Aller sur le site</span>
        </a>
    </li>
    {*<li>
        <a href="{$url}/admin/index.php?controller=dashboard&logout=1">
            <span class="fa fa-power-off"></span>
        </a>
    </li>*}
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-user"></span> {$adminProfile.firstname_admin} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="{$url}/admin/index.php?controller=employee&action=edit&edit={$adminProfile.id_admin}">
                    <span class="fa fa-cog"></span> {#edit_profile#}
                </a>
            </li>
            <li>
                <a href="{$url}/admin/index.php?controller=dashboard&logout=1">
                    <span class="fa fa-power-off"></span> {#logout#}
                </a>
            </li>
        </ul>
    </li>
</ul>