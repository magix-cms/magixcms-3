<nav id="mobile-menu1" class="mobile-menu visible-xs">
    {*<ul class="nav">
        <li class="has-submenu">
            <button type="button" class="navbar-toggle{if $smarty.server.SCRIPT_NAME =="/client.php"} open{/if}" data-toggle="collapse" data-target="#menu-client">
                <span class="fa fa-plus"></span>
            </button>
            <a href="#">
                <span class="fa fa-users"></span> Clients
            </a>
            <nav id="menu-client" class="collapse navbar-collapse{if $smarty.server.SCRIPT_NAME =="/client.php"} in{/if}">
                <ul>
                    <li>
                        <a href="#">Listes des clients</a>
                    </li>
                    <li>
                        <a href="#">Ajouter un client</a>
                    </li>
                </ul>
            </nav>
        </li>
        <li class="has-submenu">
            <button type="button" class="navbar-toggle{if $smarty.server.SCRIPT_NAME =="/defunct.php"} open{/if}" data-toggle="collapse" data-target="#menu-defunct">
                <span class="fa fa-plus"></span>
            </button>
            <a href="#">
                <span class="fa fa-folder"></span> Défunts
            </a>
            <nav id="menu-defunct" class="collapse navbar-collapse{if $smarty.server.SCRIPT_NAME =="/defunct.php"} in{/if}">
                <ul>
                    <li>
                        <a href="#">Listes des défunts</a>
                    </li>
                    <li>
                        <a href="#">Ajouter un défunt</a>
                    </li>
                </ul>
            </nav>
        </li>
        <li>
            <a href="#">
                <span class="fa fa-home"></span> Funérailles
            </a>
        </li>
        <li>
            <a href="#">
                <span class="fa fa-file"></span> Documents
            </a>
        </li>
        <li class="has-submenu{if $smarty.server.SCRIPT_NAME =="/employee.php"} active{/if}">
            <button type="button" class="navbar-toggle{if $smarty.server.SCRIPT_NAME =="/employee.php"} open{/if}" data-toggle="collapse" data-target="#menu-employee">
                <span class="fa fa-plus"></span>
            </button>
            <a href="{geturl}/employee.php">
                <span class="fa fa-user"></span> Personnel
            </a>
            <nav id="menu-employee" class="collapse navbar-collapse{if $smarty.server.SCRIPT_NAME =="/employee.php"} in{/if}">
                <ul>
                    <li>
                        <a href="{geturl}/employee.php">Listes des employés</a>
                    </li>
                    <li>
                        <a href="#">Ajouter un employé</a>
                    </li>
                </ul>
            </nav>
        </li>
    </ul>*}
    {include file="section/menu/section.tpl" mobile=true}
</nav>
<nav id="mobile-menu2" class="mobile-menu visible-xs">
    {widget_profil}
    <ul class="nav">
        <li>
            <ul class="nav">
                <li>
                    <a href="#" class="pull-left">
                        <span class="fa fa-user"></span> {$employeeData.pseudo_admin|ucfirst}
                    </a>
                </li>
                <li>
                    <a href="{geturl}/index.php?logout=1" class="pull-left">
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
                    <li>
                        <a href="{geturl}/provider.php">
                            <span class="fa fa-users"></span> {#provider#|ucfirst}
                        </a>
                    </li>
                    <li>
                        <a href="{geturl}/township.php">
                            <span class="fa fa-home"></span> {#township#|ucfirst}
                        </a>
                    </li>
                    <li>
                        <a href="{geturl}/sentence.php">
                            <span class="fa fa-list"></span> {#sentence#|ucfirst}
                        </a>
                    </li>
                    <li>
                        <a href="{geturl}/webservice.php">
                            <span class="fa fa-cloud"></span> Web Service
                        </a>
                    </li>
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