{widget_employee}
<ul class="nav navbar-nav">
    <li>
        <a href="{geturl}/{baseadmin}/index.php?controller=dashboard">
            <span class="fa fa-tachometer"></span> Tableau de bord
        </a>
    </li>
    <li>
        <a href="{geturl}" title="Aller sur le site" class="targetblank">
            <span class="fa fa-globe"></span> Aller sur le site
        </a>
    </li>
    <li>
        <a href="#">
            <span class="fa fa-pie-chart"></span> Statistiques
        </a>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-wrench"></span> Outils <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            {if {employee_access type="view" class_name="backend_controller_webservice"} eq 1}
            <li>
                <a href="{geturl}/{baseadmin}/index.php?controller=webservice.php">
                    <span class="fa fa-cloud"></span> Web Service
                </a>
            </li>
            {/if}
        </ul>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-star"></span> Accès rapide <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
        </ul>
    </li>
</ul>
<ul class="nav navbar-nav navbar-right">
    <li>
        <a href="#">
            <span class="fa fa-user"></span> {$employeeData.pseudo_admin|ucfirst}
        </a>
    </li>
    <li>
        <a href="{geturl}/admin/index.php?controller=dashboard&logout=1">
            <span class="fa fa-power-off"></span>
        </a>
    </li>
</ul>