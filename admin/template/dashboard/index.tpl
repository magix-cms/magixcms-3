{extends file="layout.tpl"}
{block name='head:title'}Tableau de bord{/block}
{block name='body:id'}home{/block}

{block name='article:header'}
    <h1 class="h2">Tableau de bord</h1>
{/block}
{block name='article:content'}
    <div class="row">
        <section id="quick-access" class="col-xs-12 col-md-6 col-lg-3">
            <header>
                <h2 class="h5"><span class="fa fa-certificate"></span> Analyse de l'installation</h2>
            </header>
            <div>
                {if is_array($getReleaseData) && !empty($getReleaseData)}
                <div class="row folder-box">
                    <div class="col-xs-6">
                        <p>Version</p>
                    </div>
                    <div class="col-xs-6">
                        <p class="text-right"><strong>{$getReleaseData.version}</strong></p>
                    </div>
                </div>
                    <div class="row folder-box">
                        <div class="col-xs-6">
                            <p>Phase</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="text-right">{$getReleaseData.phase}</p>
                        </div>
                    </div>
                {/if}
            </div>
        </section>
        <section id="tasks" class="col-xs-12 col-md-6 col-lg-3 pull-right">
            <header>
                <h2 class="h5">
                    <a href="{geturl}/{baseadmin}/index.php?controller=news">
                        <span class="fa fa-newspaper-o"></span> {#last_news#|ucfirst}
                    </a>
                </h2>
            </header>

            {if is_array($lastNews) && !empty($lastNews)}
                {foreach $lastNews as $item}
                    <div>
                        <div class="row folder-box">
                            <div class="col-xs-6">
                                <p><a href="{geturl}/{baseadmin}/index.php?controller=news&action=edit&edit={$item.id_news}">{$item.name_news}</a></p>
                            </div>
                            <div class="col-xs-6">
                                <p class="text-right">{$item.date_register|date_format:"%d/%m/%Y"}</p>
                            </div>
                        </div>
                    </div>
                {/foreach}
            {/if}
        </section>
        {if {employee_access type="view" class_name="backend_controller_pages"} eq 1}
        <section class="col-xs-12 col-md-6 col-lg-3 pull-left">
            <header>
                <h2 class="h5"><a href="{geturl}/{baseadmin}/index.php?controller=pages">
                        <span class="fa fa-file-text-o"></span> {#last_pages#|ucfirst}
                    </a>
                </h2>
            </header>

            {if is_array($lastPages) && !empty($lastPages)}
            {foreach $lastPages as $item}
            <div>
                <div class="row folder-box">
                    <div class="col-xs-6">
                        <p><a href="{geturl}/{baseadmin}/index.php?controller=pages&action=edit&edit={$item.id_pages}">{$item.name_pages}</a></p>
                    </div>
                    <div class="col-xs-6">
                        <p class="text-right">{$item.date_register|date_format:"%d/%m/%Y"}</p>
                    </div>
                </div>
            </div>
            {/foreach}
            {/if}
        </section>
        {/if}
        {if {employee_access type="view" class_name="backend_controller_employee"} eq 1}
            <section class="col-xs-12 col-md-6 col-lg-3 pull-left">
                <header>
                    <h2 class="h5"><a href="{geturl}/{baseadmin}/index.php?controller=employee">
                            <span class="fa fa-user"></span> {#employees#|ucfirst}
                        </a>
                    </h2>
                </header>
                {if is_array($employees) && !empty($employees)}
                    {foreach $employees as $item}
                        {$title_admin = "title_"|cat:$item.title_admin}
                        <div>
                            <div class="row folder-box">
                                <div class="col-xs-6">
                                    <p>{#$title_admin#} {$item.lastname_admin} {$item.firstname_admin}</p>
                                </div>
                                <div class="col-xs-6">
                                    <p class="text-right">{$item.role_name|ucfirst}</p>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </section>
        {/if}
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
{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/dashboard.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
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