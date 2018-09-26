{extends file="layout.tpl"}
{block name='head:title'}Tableau de bord{/block}
{block name='body:id'}home{/block}

{block name='article:header'}
    <h1 class="h2">Tableau de bord</h1>
{/block}
{block name='article:content'}
    <div class="row">
        <section id="quick-access" class="col-ph-12 col-lg-8 col-xl-6">
            <header>
                <h2 class="h5">Accès rapide</h2>
            </header>
            <div class="quick-links" role="group">
                <a href="{$url}/{baseadmin}/index.php?controller=news&action=add" class="btn btn-default col-ph-6 col-xs-3 col-sm-6 col-md-3">
                    <span class="fa fa-plus"></span> <span class="hidden-ph hidden-xs">Ajouter une </span><span class="add-type">actualité</span>
                </a>
                <a href="{$url}/{baseadmin}/index.php?controller=pages&action=add" class="btn btn-default col-ph-6 col-xs-3 col-sm-6 col-md-3">
                    <span class="fa fa-plus"></span> <span class="hidden-ph hidden-xs">Ajouter une </span><span class="add-type">page</span>
                </a>
                <a href="{$url}/{baseadmin}/index.php?controller=category&action=add" class="btn btn-default col-ph-6 col-xs-3 col-sm-6 col-md-3">
                    <span class="fa fa-plus"></span> <span class="hidden-ph hidden-xs">Ajouter une </span><span class="add-type">catégorie</span>
                </a>
                <a href="{$url}/{baseadmin}/index.php?controller=product&action=add" class="btn btn-default col-ph-6 col-xs-3 col-sm-6 col-md-3">
                    <span class="fa fa-plus"></span> <span class="hidden-ph hidden-xs">Ajouter un </span><span class="add-type">produit</span>
                </a>
            </div>
        </section>
    </div>
    <div class="row">
        {if {employee_access type="view" class_name="backend_controller_news"} eq 1}
        <section class="col-ph-12 col-xs-6 col-lg-3">
            <header>
                <h2 class="h5">
                    <span class="fa fa-newspaper"></span> <a href="{$url}/{baseadmin}/index.php?controller=news">{#last_news#|ucfirst}</a>
                </h2>
            </header>
            <div>
                <table class="folder-box">
                    <tbody>
                    {if is_array($lastNews) && !empty($lastNews)}
                        {foreach $lastNews as $item}
                            <tr>
                                <td>
                                    <p><a href="{$url}/{baseadmin}/index.php?controller=news&action=edit&edit={$item.id_news}">{$item.name_news}</a></p>
                                </td>
                                <td>
                                    <p class="text-right">{$item.date_register|date_format:"%d/%m/%Y"}</p>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
            </div>
        </section>
        {/if}
        {if {employee_access type="view" class_name="backend_controller_pages"} eq 1}
        <section class="col-ph-12 col-xs-6 col-lg-3">
            <header>
                <h2 class="h5">
                    <span class="fa fa-file-alt"></span> <a href="{$url}/{baseadmin}/index.php?controller=pages">{#last_pages#|ucfirst}</a>
                </h2>
            </header>
            <div>
                <table class="folder-box">
                    <tbody>
                    {if is_array($lastPages) && !empty($lastPages)}
                        {foreach $lastPages as $item}
                            <tr>
                                <td>
                                    <p><a href="{$url}/{baseadmin}/index.php?controller=pages&action=edit&edit={$item.id_pages}">{$item.name_pages}</a></p>
                                </td>
                                <td>
                                    <p class="text-right">{$item.date_register|date_format:"%d/%m/%Y"}</p>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
            </div>
        </section>
        {/if}
        {if {employee_access type="view" class_name="backend_controller_category"} eq 1}
        <section class="col-ph-12 col-xs-6 col-lg-3">
            <header>
                <h2 class="h5">
                    <span class="fa fa-folder-open"></span> <a href="{$url}/{baseadmin}/index.php?controller=category">{#last_categories#|ucfirst}</a>
                </h2>
            </header>
            <div>
                <table class="folder-box">
                    <tbody>
                    {if is_array($lastCats) && !empty($lastCats)}
                        {foreach $lastCats as $item}
                            <tr>
                                <td>
                                    <p><a href="{$url}/{baseadmin}/index.php?controller=category&action=edit&edit={$item.id_cat}">{$item.name_cat}</a></p>
                                </td>
                                <td>
                                    <p class="text-right">{$item.date_register|date_format:"%d/%m/%Y"}</p>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
            </div>
        </section>
        {/if}
        {if {employee_access type="view" class_name="backend_controller_product"} eq 1}
        <section class="col-ph-12 col-xs-6 col-lg-3">
            <header>
                <h2 class="h5">
                    <span class="fa fa-box"></span> <a href="{$url}/{baseadmin}/index.php?controller=product">{#last_products#|ucfirst}</a>
                </h2>
            </header>
            <div>
                <table class="folder-box">
                    <tbody>
                    {if is_array($lastProducts) && !empty($lastProducts)}
                        {foreach $lastProducts as $item}
                            <tr>
                                <td>
                                    <p><a href="{$url}/{baseadmin}/index.php?controller=product&action=edit&edit={$item.id_product}">{$item.name_p}</a></p>
                                </td>
                                <td>
                                    <p class="text-right">{$item.date_register|date_format:"%d/%m/%Y"}</p>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                    </tbody>
                </table>
            </div>
        </section>
        {/if}
        {*{if {employee_access type="view" class_name="backend_controller_employee"} eq 1}
            <section class="col-ph-12 col-xs-6 col-lg-3 pull-left">
                <header>
                    <h2 class="h5"><a href="{$url}/{baseadmin}/index.php?controller=employee">
                            <span class="fa fa-user"></span> {#employees#|ucfirst}
                        </a>
                    </h2>
                </header>
                {if is_array($employees) && !empty($employees)}
                    {foreach $employees as $item}
                        {$title_admin = "title_"|cat:$item.title_admin}
                        <div>
                            <div class="row folder-box">
                                <div class="col-ph-6">
                                    <p>{#$title_admin#} {$item.lastname_admin} {$item.firstname_admin}</p>
                                </div>
                                <div class="col-ph-6">
                                    <p class="text-right">{$item.role_name|ucfirst}</p>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </section>
        {/if}*}
        {*<section class="col-ph-12 col-lg-9">
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
        </section>*}
    </div>
{/block}
{*block name="foot" append}
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
{/block*}