{extends file="layout.tpl"}
{block name='head:title'}Tableau de bord{/block}
{block name='body:id'}home{/block}

{block name='article:header'}
    <h1 class="h2">Tableau de bord</h1>
{/block}
{block name='article'}
<div id="dashboard" class="col-xs-12 col-sm-12 col-md-12">
    <header>
        <button id="toggle-menu" type="button" class="open-menu navbar-toggle" data-target="#mobile-menu1">
            <span class="sr-only">{#toggleNavigation#|ucfirst}</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <button id="toggle-menu2" type="button" class="open-menu navbar-toggle pull-right" data-target="#mobile-menu2">
            <span class="sr-only">{#toggleNavigation#|ucfirst}</span>
            <span class="fa fa-cog"></span>
        </button>
        {block name='article:header'}{/block}
    </header>
    {block name='article:content'}
        <div class="row">
            <section id="quick-access" class="col-xs-12 col-md-6 col-lg-3">
                <header>
                    <h2 class="h5">Accès rapide</h2>
                </header>
                <div>
                    <ul class="nav">
                       {* {if {employee_access type="add_access" class_name="frontend_controller_customer"} eq 1}
                        <li><a href="{geturl}/customer.php?action=add"><span class="fa fa-plus"></span> Ajouter un client</a></li>
                        {/if}
                        {if {employee_access type="view_access" class_name="frontend_controller_customer"} eq 1}
                        <li><a href="{geturl}/customer.php"><span class="fa fa-list"></span> Liste des clients</a></li>
                        {/if}
                        {if {employee_access type="view_access" class_name="frontend_controller_defunct"} eq 1}
                        <li><a href="{geturl}/defunct.php"><span class="fa fa-list-alt"></span> Liste des défunts</a></li>
                        {/if}*}
                    </ul>
                </div>
            </section>
            <section id="tasks" class="col-xs-12 col-md-6 col-lg-3 pull-right">
                <header>
                    <h2 class="h5">En cours</h2>
                </header>
                <div>
                    <div class="row folder-box">
                        <div class="col-xs-6">
                            <p><a href="#">ma page</a></p>
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right">02/11/2016</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-xs-12 col-md-6 col-lg-3 pull-left">
                <header>
                    <h2 class="h5">Dernières pages</h2>
                </header>
                <div>
                    <div class="row folder-box">
                        <div class="col-xs-6">
                            <p><a href="#">ma page</a></p>
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right">02/11/2016</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-xs-12 col-md-6 col-lg-3 pull-left">
                <header>
                    <h2 class="h5">Derniers </h2>
                </header>
                <div>
                    <div class="row folder-box">
                        <div class="col-xs-6">
                            <p><a href="#">ma page</a></p>
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right">02/11/2016</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-xs-12 col-lg-9">
                <header>
                    <h2 class="h5">Statistiques</h2>
                </header>
                <div id="stats">
                    <nav>
                        <ul class="nav nav-justified">
                            <li class="active">
                                <a href="#" class="cchart active current" data-dataset="0" data-chart="line" data-content="sales" title="Consulter les statistiques des pages">
                                    <span>Pages</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cchart" data-dataset="1" data-chart="pie" data-content="orders" title="Consulter les statistiques des Commandes">
                                    <span>Actualités</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cchart" data-dataset="2" data-chart="line" data-content="avcarts" title="Consulter les statistiques des Paniers Moyen">
                                    <span>Produits</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div id="chart-container">
                        <canvas id="chart" height="8" width="20"></canvas>
                    </div>
                </div>
            </section>
        </div>
    {/block}
</div>
{/block}
{block name="aside"}{/block}
{block name="foot" append}
    {script src="/{baseadmin}/min/?f={baseadmin}/template/js/dashboard.min.js" type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof dashboard == "undefined")
            {
                console.log("dashboard is not defined");
            }else{
                dashboard.run({$kpl});
            }
        });
    </script>
{/block}