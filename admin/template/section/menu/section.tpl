{if !isset($mobile)}
    {$mobile = false}
{/if}
<ul class="nav main-menu">
    {include file="section/menu/main.tpl" class="visible-ph visible-xs"}
    <li class="visible-ph visible-xs"><hr></li>
    {if {employee_access type="view" class_name="backend_controller_home"} eq 1}
    <li class="{if $smarty.get.controller == 'home'}active{/if}">
        <a href="{$url}/{baseadmin}/index.php?controller=home">
            <span class="fa fa-home"></span> {#root_home#}
        </a>
    </li>
    {/if}
    {if {employee_access type="view" class_name="backend_controller_pages"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'pages'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'pages'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-pages">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=pages">
            <span class="fa fa-file-alt"></span> {#root_pages#}
        </a>
        <nav id="nav-pages" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'pages'} in{/if}">
            <ul class="nav list-unstyled">
                <li{if $smarty.get.controller == 'pages'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=pages">{#list_page#}</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_pages"} eq 1}
                    <li{if $smarty.get.controller == 'pages' && $smarty.get.action == 'add'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=pages&action=add">{#add_page#}</a>
                    </li>
                {/if}
            </ul>
        </nav>
    </li>
    {/if}
    {if {employee_access type="view" class_name="backend_controller_news"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'news'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'news'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-news">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=news">
            <span class="fa fa-newspaper"></span> {#root_news#}
        </a>
        <nav id="nav-news" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'news'} in{/if}">
            <ul class="nav list-unstyled">
                <li{if $smarty.get.controller == 'news'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=news">{#list_news#}</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_pages"} eq 1}
                    <li{if $smarty.get.controller == 'news' && $smarty.get.action == 'add'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=news&action=add">{#add_new#}</a>
                    </li>
                {/if}
            </ul>
        </nav>
    </li>
    {/if}
    {if {employee_access type="view" class_name="backend_controller_catalog"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'catalog' || $smarty.get.controller == 'category' || $smarty.get.controller == 'product'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'catalog' || $smarty.get.controller == 'category' || $smarty.get.controller == 'product'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-catalog">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=catalog">
            <span class="fa fa-shopping-cart"></span> {#root_catalog#}
        </a>
        {if {employee_access type="view" class_name="backend_controller_category"} eq 1 || {employee_access type="view" class_name="backend_controller_product"} eq 1 }
        <nav id="nav-catalog" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'catalog' || $smarty.get.controller == 'category' || $smarty.get.controller == 'product'} in{/if}">
            <ul class="nav list-unstyled">
                {if {employee_access type="view" class_name="backend_controller_category"} eq 1}
                <li{if $smarty.get.controller == 'category'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=category">{#list_cats#}</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_category"} eq 1}
                <li{if $smarty.get.controller == 'category' && $smarty.get.action == 'add'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=category&action=add">{#add_cat#}</a>
                </li>
                {/if}
                {/if}
                {if {employee_access type="view" class_name="backend_controller_category"} eq 1}
                <li{if $smarty.get.controller == 'product'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=product">{#list_products#}</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_product"} eq 1}
                <li{if $smarty.get.controller == 'product' && $smarty.get.action == 'add'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=product&action=add">{#add_products#}</a>
                </li>
                {/if}
                {/if}
            </ul>
        </nav>
        {/if}
    </li>
    {/if}
    <li><hr></li>
    {if {employee_access type="view" class_name="backend_controller_about"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'about'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'about'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-about">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=about">
            <span class="fa fa-briefcase"></span> {#root_about#}
        </a>
        <nav id="nav-about" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'about'} in{/if}">
            <ul class="nav list-unstyled">
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=about&tab=company">
                        <span class="fa fa-info"></span> {#info_company#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=about&tab=contact">
                        <span class="fa fa-phone"></span> {#info_contact#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=about&tab=socials">
                        <span class="fab fa-facebook-f"></span> {#info_socials#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=about&tab=opening">
                        <span class="far fa-clock"></span> {#info_opening#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=about&tab=text">
                        <span class="fa fa-question"></span> {#text#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=about&tab=page">
                        <span class="fa fa-file"></span> {#info_page#}
                    </a>
                </li>
            </ul>
        </nav>
    </li>
    {/if}
    {if {employee_access type="view" class_name="backend_controller_theme"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'theme'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'theme'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-theme">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=theme">
            <span class="fa fa-desktop"></span> {#appearance#}
        </a>
        <nav id="nav-theme" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'theme'} in{/if}">
            <ul class="nav list-unstyled">
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=theme&tab=theme">
                        <span class="fa fa-desktop"></span> {#info_theme#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=theme&tab=menu">
                        <span class="fa fa-bars"></span> {#info_menu#}
                    </a>
                </li>
                <li>
                    <a href="{$url}/{baseadmin}/index.php?controller=theme&tab=share">
                        <span class="fa fa-share"></span> {#info_share#}
                    </a>
                </li>
            </ul>
        </nav>
    </li>
    {/if}
    <li class="has-submenu{if $smarty.get.controller == 'setting' || $smarty.get.controller == 'files' || $smarty.get.controller == 'webservice' || $smarty.get.controller == 'domain' || $smarty.get.controller == 'seo'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'setting' || $smarty.get.controller == 'files' || $smarty.get.controller == 'webservice' || $smarty.get.controller == 'domain' || $smarty.get.controller == 'seo'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-setting">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        {*<a href="#">*}
            <span><span class="fa fa-cog"></span> {#config#|ucfirst}</span>
        {*</a>*}
        <nav id="nav-setting" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'setting' || $smarty.get.controller == 'files' || $smarty.get.controller == 'webservice' || $smarty.get.controller == 'domain' || $smarty.get.controller == 'seo'} in{/if}">
            <ul class="nav list-unstyled">
                {if {employee_access type="view" class_name="backend_controller_setting"} eq 1}
                    <li{if $smarty.get.controller == 'setting'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=setting">
                            <span class="fa fa-cog"></span> {#setting#}
                        </a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_files"} eq 1}
                    <li{if $smarty.get.controller == 'files'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=files">
                            <span class="fa fa-file"></span> {#files_and_images#}
                        </a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_webservice"} eq 1}
                    <li{if $smarty.get.controller == 'webservice'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=webservice">
                            <span class="fa fa-cloud"></span> {#webservice#}
                        </a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_domain"} eq 1}
                    <li{if $smarty.get.controller == 'domain'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=domain">
                            <span class="fa fa-link"></span> {#domain_sitemap#}
                        </a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_seo"} eq 1}
                    <li{if $smarty.get.controller == 'seo'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=seo">
                            <span class="far fa-file-alt"></span> {#seo#}
                        </a>
                    </li>
                {/if}
            </ul>
        </nav>
    </li>
    <li class="has-submenu{if $smarty.get.controller == 'language' || $smarty.get.controller == 'country' || $smarty.get.controller == 'translate'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'language' || $smarty.get.controller == 'country' || $smarty.get.controller == 'translate'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-lang">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        {*<a href="#">*}
            <span><span class="fa fa-location-arrow"></span> {#localisation#}</span>
        {*</a>*}
        <nav id="nav-lang" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'language' || $smarty.get.controller == 'country' || $smarty.get.controller == 'translate'} in{/if}">
            <ul class="nav list-unstyled">
                {if {employee_access type="view" class_name="backend_controller_language"} eq 1}
                    <li{if $smarty.get.controller == 'language'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=language">
                            <span class="fa fa-flag"></span> {#language#}
                        </a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_country"} eq 1}
                    <li{if $smarty.get.controller == 'country'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=country">
                            <span class="fa fa-globe"></span> {#country#}
                        </a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_translate"} eq 1}
                    <li{if $smarty.get.controller == 'country'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=translate">
                            <span class="fa fa-language"></span> {#translate#}
                        </a>
                    </li>
                {/if}
            </ul>
        </nav>
    </li>
    {if {employee_access type="view" class_name="backend_controller_employee"} eq 1}
    <li class="has-submenu{if $smarty.get.controller == 'employee' || $smarty.get.controller == 'access'} active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'employee'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-employee">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=employee">
            <span class="fa fa-user"></span> {#administration#}
        </a>
        <nav id="nav-employee" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'employee'} in{/if}">
            <ul class="nav list-unstyled">
                <li{if $smarty.get.controller == 'employee'} class="active"{/if}>
                    <a href="{$url}/{baseadmin}/index.php?controller=employee">{#list_employees#}</a>
                </li>
                {if {employee_access type="append" class_name="backend_controller_employee"} eq 1}
                    <li{if $smarty.get.controller == 'employee' && $smarty.get.action == 'add'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=employee&action=add">{#add_employees#}</a>
                    </li>
                {/if}
                {if {employee_access type="view" class_name="backend_controller_access"} eq 1}
                    <li{if $smarty.get.controller == 'access'} class="active"{/if}>
                        <a href="{$url}/{baseadmin}/index.php?controller=access">{#perms#}</a>
                    </li>
                {/if}
            </ul>
        </nav>
    </li>
    {/if}
    <li><hr></li>
    {if {employee_access type="view" class_name="backend_controller_plugins"} eq 1}
    <li class="has-submenu {if $smarty.get.controller == 'plugins' || (!in_array($smarty.get.controller,array('dashboard','home','pages','news','catalog','category','product','about','theme','setting','files','webservice','domain','seo','language','country','translate','employee','access')) && $smarty.get.controller)}active{/if}">
        <button type="button" class="navbar-toggle{if $smarty.get.controller == 'plugins'} open{/if}" data-toggle="collapse" data-parent="#{$menuId}" data-target="#nav-plugins">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        <a href="{$url}/{baseadmin}/index.php?controller=plugins">
            <span class="fa fa-cogs"></span> Extensions
        </a>
        {if is_array($getItemsPlugins) && !empty($getItemsPlugins)}
        <nav id="nav-plugins" class="collapse{* navbar-collapse*}{if $smarty.get.controller == 'plugins'} in{/if}">
            <ul class="nav list-unstyled">
                {foreach $getItemsPlugins as $item}
                {if {employee_access type="view" class_name="plugins_{$item.name}_admin"} eq 1}
                <li class="{if $smarty.get.controller == {$item.name}}active{/if}">
                    <a href="{$url}/{baseadmin}/index.php?controller={$item.name}">
                        <span class="fa {if $smarty.get.controller == {$item.name}}fa-angle-right{else}fa-angle-down{/if}"></span> {$item.title}
                    </a>
                </li>
                {/if}
                {/foreach}
            </ul>
        </nav>
        {/if}
    </li>
    {/if}
</ul>