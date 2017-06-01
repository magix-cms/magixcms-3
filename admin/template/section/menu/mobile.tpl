<nav id="mobile-menu1" class="mobile-menu visible-xs">
    {include file="section/menu/section.tpl" mobile=true}
</nav>
<nav id="mobile-menu2" class="mobile-menu visible-xs">
    {widget_employee}
    <ul class="nav">
        <li>
            <ul class="nav">
                <li>
                    <a href="#" class="pull-left">
                        <span class="fa fa-user"></span> {$employeeData.pseudo_admin|ucfirst}
                    </a>
                </li>
                <li>
                    <a href="{geturl}/admin/index.php?controller=dashboard&logout=1" class="pull-left">
                        <span class="fa fa-power-off"></span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{geturl}/index.php">
                <span class="fa fa-tachometer"></span> Tableau de bord
            </a>
        </li>
        <li>
            <a href="#">
                <span class="fa fa-globe"></span> Aller sur le site
            </a>
        </li>
        <li>
            <a href="#">
                <span class="fa fa-pie-chart"></span> Statistiques
            </a>
        </li>
        <li>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-tools">
                <span class="caret"></span>
            </button>
            <a href="#">
                <span class="fa fa-wrench"></span> Outils
            </a>
            <nav id="menu-tools" class="collapse navbar-collapse">
                <ul>
                    {if {employee_access type="view" class_name="backend_controller_webservice"} eq 1}
                    <li>
                        <a href="{geturl}/{baseadmin}/index.php?controller=webservice.php">
                            <span class="fa fa-cloud"></span> Web Service
                        </a>
                    </li>
                    {/if}
                    {if {employee_access type="view" class_name="backend_controller_domain"} eq 1}
                        <li>
                            <a href="{geturl}/{baseadmin}/index.php?controller=domain">
                                <span class="fa fa-link"></span> {#domain#}
                            </a>
                        </li>
                    {/if}
                </ul>
            </nav>
        </li>
        <li>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-localisation">
                <span class="caret"></span>
            </button>
            <a href="#">
                <span class="fa fa-location-arrow"></span> Localisation
            </a>
            <nav id="menu-localisation" class="collapse navbar-collapse">
                <ul>
                    {if {employee_access type="view" class_name="backend_controller_language"} eq 1}
                        <li>
                            <a href="{geturl}/{baseadmin}/index.php?controller=language">
                                <span class="fa fa-flag"></span> {#language#}
                            </a>
                        </li>
                    {/if}
                    {if {employee_access type="view" class_name="backend_controller_country"} eq 1}
                        <li>
                            <a href="{geturl}/{baseadmin}/index.php?controller=country">
                                <span class="fa fa-globe"></span> {#country#}
                            </a>
                        </li>
                    {/if}
                </ul>
            </nav>
        </li>
        <li class="has-submenu dropdown">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-quick-access">
                <span class="caret"></span>
            </button>
            <a href="#">
                <span class="fa fa-star"></span> Accès rapide
            </a>
            <nav id="menu-quick-access" class="collapse navbar-collapse">
                <ul>
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                </ul>
            </nav>
        </li>
    </ul>
</nav>