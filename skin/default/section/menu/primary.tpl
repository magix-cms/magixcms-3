{strip}
{* Smarty switch to detect current element *}
{switch $smarty.server.SCRIPT_NAME}
    {* Home *}
{case '/index.php' break}
{assign var="home_current" value=1}
    {* Pages *}
{case '/cms.php' break}
{if isset($smarty.get.getidpage_p)}
    {assign var="pageSection" value=$smarty.get.getidpage_p}
{else}
    {assign var="pageSection" value=$smarty.get.getidpage}
{/if}
{assign var="activePage" value=$smarty.get.getidpage}
    {* Catalogue *}
{case '/catalog.php' break}
{if isset($smarty.get.idclc)}
    {assign var="parentCat" value=$smarty.get.idclc}
{/if}
{if isset($smarty.get.idcls)}
    {assign var="subCat" value=$smarty.get.idcls}
{/if}
    {* Actualités *}
{case '/news.php' break}
{assign var="news_current" value=1}
    {* Plugins *}
{case '/plugins.php' break}
{switch $smarty.get.magixmod}
    {* Contact *}
{case 'contact' break}
{assign var="contact_current" value=1}
    {* About *}
{case 'about' break}
{assign var="about_current" value=1}
    {* FAQ *}
{case 'faq' break}
{assign var="faq_current" value=1}
    {* Gmap *}
{case 'gmap' break}
{assign var="gmap_current" value=1}
{assign var="contact_current" value=1}
{/switch}
{/switch}


{* --- Array Menu --- *}
{assign var=menu value=array()}

{* --- Check Active Roots --- *}
{if !isset($root)}
    {$root = [
    'home'      => true,
    'about'     => false,
    'catalog'   => true,
    'news'      => true,
    'contact'   => true
    ]}
{else}
    {$basic_root = ['home','about','catalog','news','contact']}
    {foreach $basic_root as $section}
        {if !isset($root[$section])}
            {if $section != 'about'}
                {$root[$section] = true}
            {else}
                {$root[$section] = false}
            {/if}
        {/if}
    {/foreach}
{/if}

{* --- Enable Page links --- *}
{if !isset($mobile)}
    {$mobile = false}
{/if}

{* --- Enable Page links --- *}
{if !isset($pages)}
    {$pages = true}
{/if}

{* --- Enable Catalog links --- *}
{if !isset($catalog)}
    {$catalog = true}
{/if}

{* --- Disable submenu links --- *}
{$dropmenu = ['dropdown','tabs','tabs-arrow']}
{if (isset($submenu) && $submenu) || $type|in_array:$dropmenu}
    {$getPage = 'all'}
    {$getCat = ['category' => 'subcategory']}
{else}
    {$getPage = 'parent'}
    {$getCat = 'category'}
{/if}
{if $type == 'mega-dropdown'}
    {$getCat = ['category' => 'subcategory']}
{/if}

{* --- Disable Gmap link --- *}
{if !isset($gmap)}
    {$gmap = false}
{/if}

{* --- Disable FAQ link --- *}
{if !isset($faq)}
    {$faq = false}
{/if}


{* --- Menu Home --- *}
{if $root.home}
    {$menu[] = [
    'name'      => {#home#},
    'url'       => "{geturl}/{getlang}/",
    'title'     => {#show_home#},
    'active'    => $home_current
    ]}
{/if}


{* --- Menu About --- *}
{if $root.about && $about != null}
    {$item = [
    'name'      => {#about_label#},
    'url'       => "{geturl}/{getlang}/about/",
    'title'     => "{#about_title#} {#website_name#}",
    'active'    => $about_current
    ]}
    {if $about.childs != null && (isset($submenu) && $submenu)}
        {assign var=submenu value=array()}
        {foreach $about.childs as $child}
            {if isset($smarty.get.pnum1) && $smarty.get.pnum1 == $child.id}
                {$subactive = 1}
            {else}
                {$subactive = 0}
            {/if}

            {$submenu[] = [
            'name'      => {$child.title},
            'url'       => "{geturl}/{getlang}/about/{$child.uri}-{$child.id}/",
            'title'     => {$child.title},
            'active'    => {$subactive}
            ]}
        {/foreach}
        {$item['subdata'] = $submenu}
    {/if}
    {$menu[] = $item}
{/if}


{* --- Menu Pages --- *}
{if {#menu_pages#} && $pages}
    {if !empty({#menu_pages_title#})}
        {$titles = ';'|explode:#menu_pages_title#}
    {/if}
    {widget_cms_data
        conf = [
            'select' => [{getlang} => {#menu_pages#}],
            'context' => {$getPage}
            ]
        assign="pageList"
    }
    {foreach $pageList as $page}
        {if $pageSection && $page.id == $pageSection}
            {$active = 1}
        {else}
            {$active = 0}
        {/if}
        {if isset($titles[$page@index]) && !empty($titles[$page@index])}
            {$title = $titles[$page@index]}
        {else}
            {$title = $page.name}
        {/if}
        {$item = [
            'name'      => {$title},
            'url'       => {$page.url},
            'title'     => {$page.name},
            'active'    => {$active}
        ]}
        {$subdata = 0}
        {if $page.subdata}
            {assign var=subdata value=array()}
            {foreach $page.subdata as $child}
                {if $pageSection && $child.id == $activePage}
                    {$subactive = 1}
                {else}
                    {$subactive = 0}
                {/if}
                {$subdata[] = [
                'name'      => {$child.name},
                'url'       => {$child.url},
                'title'     => {$child.name},
                'active'    => {$subactive}
                ]}
            {/foreach}
            {$item['subdata'] = $subdata}
        {/if}
        {$menu[] = $item}
    {/foreach}
{/if}


{* --- Menu Catalog --- *}
{if {#menu_catalog#} && $catalog}
    {widget_catalog_data
        conf = [
            'context' => {$getCat},
            'select' => [{getlang} => {#menu_catalog#}],
            'sort' => ['order'=>'ASC']
            ]
        assign="categoryList"
    }
    {foreach $categoryList as $category}
        {if $parentCat && $category.id == $parentCat}
            {$active = 1}
        {else}
            {$active = 0}
        {/if}
        {$item = [
        'name'      => {$category.name},
        'url'       => {$category.url},
        'title'     => {$category.name},
        'active'    => {$active}
        ]}
        {$subdata = 0}
        {if $category.subdata}
            {assign var=subdata value=array()}
            {foreach $category.subdata as $child}
                {if $subCat && $child.id == $subCat}
                    {$subactive = 1}
                {else}
                    {$subactive = 0}
                {/if}
                {$subdata[] = [
                'name'      => {$child.name},
                'url'       => {$child.url},
                'title'     => {$child.name},
                'active'    => {$subactive}
                ]}
            {/foreach}
            {$item['subdata'] = $subdata}
        {/if}
        {$menu[] = $item}
    {/foreach}
{elseif $root.catalog}
    {if $smarty.server.SCRIPT_NAME == '/catalog.php'}
        {$active = 1}
    {else}
        {$active = 0}
    {/if}

    {$item = [
    'name'      => {#catalog#},
    'url'       => "{geturl}/{getlang}/{#nav_catalog_uri#}/",
    'title'     => {#catalog#},
    'active'    => {$active}
    ]}

    {if isset($submenu) && $submenu || $type|in_array:$dropmenu}
        {widget_catalog_data
            conf = [
                'context' => {$getCat},
                'sort' => ['order'=>'ASC']
                ]
            assign="categoryList"
        }
        {if $categoryList != null}
            {assign var=subdata value=array()}
            {foreach $categoryList as $category}
                {if $parentCat && $category.id == $parentCat}
                    {$subactive = 1}
                {else}
                    {$subactive = 0}
                {/if}

                {$submenu = [
                'name'      => {$category.name},
                'url'       => {$category.url},
                'title'     => {$category.name},
                'active'    => {$subactive}
                ]}

                {if isset($category.subdata)}
                    {assign var=sub value=array()}
                    {foreach $category.subdata as $child}
                        {if $subCat && $child.id == $subCat}
                            {$subactive = 1}
                        {else}
                            {$subactive = 0}
                        {/if}
                        {$sub[] = [
                        'name'      => {$child.name},
                        'url'       => {$child.url},
                        'title'     => {$child.name},
                        'active'    => {$subactive}
                        ]}
                    {/foreach}
                    {$submenu['subdata'] = $sub}
                {/if}

                {$subdata[] = $submenu}
            {/foreach}
            {$item['subdata'] = $subdata}
        {/if}
    {/if}

    {$menu['catalog'] = $item}
{/if}


{* --- Menu Pages --- *}
{if {#menu_pages_2#} && $pages}
    {if !empty({#menu_pages_title_2#})}
        {$titles = ';'|explode:#menu_pages_title_2#}
    {/if}
    {widget_cms_data
        conf = [
            'select' => [{getlang} => {#menu_pages_2#}],
            'context' => {$getPage}
            ]
        assign="pageList"
    }
    {foreach $pageList as $page}
        {if $pageSection && $page.id == $pageSection}
            {$active = 1}
        {else}
            {$active = 0}
        {/if}
        {if isset($titles[$page@index]) && !empty($titles[$page@index])}
            {$title = $titles[$page@index]}
        {else}
            {$title = $page.name}
        {/if}
        {$item = [
        'name'      => {$title},
        'url'       => {$page.url},
        'title'     => {$page.name},
        'active'    => {$active}
        ]}
        {$subdata = 0}
        {if $page.subdata}
            {assign var=subdata value=array()}
            {foreach $page.subdata as $child}
                {if $pageSection && $child.id == $activePage}
                    {$subactive = 1}
                {else}
                    {$subactive = 0}
                {/if}
                {$subdata[] = [
                'name'      => {$child.name},
                'url'       => {$child.url},
                'title'     => {$child.name},
                'active'    => {$subactive}
                ]}
            {/foreach}
            {$item['subdata'] = $subdata}
        {/if}
        {$menu[] = $item}
    {/foreach}
{/if}


{* --- Menu News --- *}
{if $root.news}
    {$menu[] = [
    'name'      => {#news#},
    'url'       => "{geturl}/{getlang}/{#nav_news_uri#}/",
    'title'     => {#show_news#},
    'active'    => {$news_current}
    ]}
{/if}


{* --- Menu FAQ --- *}
{if $faq}
    {$contact = [
    'name'      => {#faq_label#},
    'url'       => "{geturl}/{getlang}/faq/",
    'title'     => {#faq_title#},
    'active'    => $faq_current
    ]}
    {$menu[] = $contact}
{/if}


{* --- Menu Contact --- *}
{if $root.contact}
    {$contact = [
    'name'      => {#contact#},
    'url'       => "{geturl}/{getlang}/contact/",
    'title'     => {#show_contact_form#},
    'active'    => $contact_current
    ]}
    {if $gmap && (isset($submenu) && $submenu)}
        {$contact['subdata'][] = [
        'name'      => {#plan_acces#},
        'url'       => "{geturl}/{getlang}/gmap/",
        'title'     => {#plan_acces#},
        'active'    => $gmap_current
        ]}
    {/if}
    {$menu[] = $contact}
{/if}
{/strip}
<div{if !$main} id="menu"{if $mobile} class="collapse"{/if}{else} class="visible-md visible-lg"{/if}>
    {if !$main}
    <div id="menu-overlay" data-toggle="collapse" data-target="#menu"></div>
    <div id="sidebar">
        <header>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                <i class="material-icons">close</i>
                <span class="sr-only">{#closeNavigation#|ucfirst}</span>
            </button>
            Menu
        </header>{/if}
        <nav id="{if $main}main{else}side{/if}-menu" class="menu menu-tabs-arrow menubar" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
            <a href="#content" class="sr-only skip-menu">{#skipMenu#}</a>
            <ul id="menu-ul" class="nav">
                {include file="section/menu/loop/dropdown.tpl" menuData=$menu mobile=$mobile}
            </ul>
        </nav>
        {if !$main}
        <footer class="followbox">
            <span class="sr-only">{#follow_us#|ucfirst}</span>
            <ul class="list-unstyled list-inline">
                {if $companyData.socials.facebook != null}
                    <li>
                        <a href="{$companyData.socials.facebook}" title="{#fb_follow_title#|ucfirst}">
                            <span class="fa fa-facebook"></span><span class="sr-only">{#fb_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter != null}
                    <li>
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}">
                            <span class="fa fa-twitter"></span><span class="sr-only">{#tw_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.google != null}
                    <li>
                        <a href="{$companyData.socials.google}" title="{#gg_follow_title#|ucfirst}" rel="publisher">
                            <span class="fa fa-google-plus"></span><span class="sr-only">{#gg_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin != null}
                    <li>
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}">
                            <span class="fa fa-linkedin"></span><span class="sr-only">{#lk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </footer>
    </div>
    {/if}
</div>