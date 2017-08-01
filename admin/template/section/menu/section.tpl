{if !isset($mobile)}
    {$mobile = false}
{/if}
<ul class="nav">
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
    {if {employee_access type="view" class_name="backend_controller_news"} eq 1}
        <li class="has-submenu{if $smarty.get.controller == 'news'} active{/if}">
            <a href="{geturl}/{baseadmin}/index.php?controller=news">
                <span class="fa fa-newspaper-o"></span> {#root_news#}
            </a>
            <ul class="nav list-unstyled">
                <li{if $smarty.get.controller == 'news'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=news">Listes des Actualités</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_pages"} eq 1}
                    <li{if $smarty.get.action == 'add'} class="active"{/if}>
                        <a href="{geturl}/{baseadmin}/index.php?controller=news&action=add">Ajouter une actualité</a>
                    </li>
                {/if}
            </ul>
        </li>
    {/if}

    <li class="has-submenu{if $smarty.get.controller == 'catalog' || $smarty.get.controller == 'category'} active{/if}">
        <a href="#">
            <span class="fa fa-shopping-cart"></span> {#root_catalog#}
        </a>
        <ul class="nav list-unstyled">
            <li{if $smarty.get.controller == 'category'} class="active"{/if}>
                <a href="{geturl}/{baseadmin}/index.php?controller=category">Listes des categories</a>
            </li>
            {if {employee_access type="append" class_name="backend_controller_category"} eq 1}
                <li{if $smarty.get.action == 'add'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=category&action=add">Ajouter une categorie</a>
                </li>
            {/if}
        </ul>
    </li>

    <li><hr></li>
    <li class="{if $smarty.get.controller == 'about'}active{/if}">
        <a href="{geturl}/{baseadmin}/index.php?controller=about">
            <span class="fa fa-briefcase"></span> {#root_about#}
        </a>
    </li>
    <li class="has-submenu{if $smarty.get.controller == 'setting' || $smarty.get.controller == 'files' || $smarty.get.controller == 'webservice' || $smarty.get.controller == 'domain'} active{/if}">
        <a href="#">
            <span class="fa fa-cog"></span> Configuration
        </a>
        <ul class="nav list-unstyled">
            {if {employee_access type="view" class_name="backend_controller_setting"} eq 1}
                <li{if $smarty.get.controller == 'setting'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=setting">
                        <span class="fa fa-cog"></span> {#setting#}
                    </a>
                </li>
            {/if}
            {if {employee_access type="view" class_name="backend_controller_files"} eq 1}
                <li{if $smarty.get.controller == 'files'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=files">
                        <span class="fa fa-file"></span> {#files_and_images#}
                    </a>
                </li>
            {/if}
            {if {employee_access type="view" class_name="backend_controller_webservice"} eq 1}
                <li{if $smarty.get.controller == 'webservice'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=webservice">
                        <span class="fa fa-cloud"></span> Web Service
                    </a>
                </li>
            {/if}
            {if {employee_access type="view" class_name="backend_controller_domain"} eq 1}
                <li{if $smarty.get.controller == 'domain'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=domain">
                        <span class="fa fa-link"></span> {#domain#}
                    </a>
                </li>
            {/if}
        </ul>
    </li>
    <li class="has-submenu{if $smarty.get.controller == 'language' || $smarty.get.controller == 'country'} active{/if}">
        <a href="#">
            <span class="fa fa-location-arrow"></span> Localisation
        </a>
        <ul class="nav list-unstyled">
            {if {employee_access type="view" class_name="backend_controller_language"} eq 1}
                <li{if $smarty.get.controller == 'language'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=language">
                        <span class="fa fa-flag"></span> {#language#}
                    </a>
                </li>
            {/if}
            {if {employee_access type="view" class_name="backend_controller_country"} eq 1}
                <li{if $smarty.get.controller == 'country'} class="active"{/if}>
                    <a href="{geturl}/{baseadmin}/index.php?controller=country">
                        <span class="fa fa-globe"></span> {#country#}
                    </a>
                </li>
            {/if}
        </ul>
    </li>
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
    <li><hr></li>
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