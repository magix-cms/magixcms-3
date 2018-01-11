{strip}
{* --- Array Menu --- *}
{foreach $links as $k => $link}
    {if $link.mode_link !== 'simple'}
        {strip}
            {if $link.type_link eq 'home'}
                {if $link.mode_link eq 'dropdown'}{$context = 'parent'}{else}{$context = 'all'}{/if}
                {widget_cms_data
                conf = [
                'context' => $context,
                'type' => 'menu'
                ]
                assign="pages"
                }
            {elseif $link.type_link eq 'about'}
                {if $link.mode_link eq 'dropdown'}{$context = 'parent'}{else}{$context = 'all'}{/if}
                {widget_about_data
                conf = [
                'context' => $context,
                'type' => 'menu'
                ]
                assign="pages"
                }
            {elseif $link.type_link eq 'about_page'}
                {if $link.mode_link eq 'dropdown'}{$context = 'child'}{else}{$context = 'all'}{/if}
                {widget_about_data
                conf = [
                'context' => $context,
                'select' => [{getlang} => $link.id_page],
                'type' => 'menu'
                ]
                assign="pages"
                }
                {$pages = $pages[0].subdata}
            {elseif $link.type_link eq 'pages'}
                {widget_cms_data
                conf = [
                'context' => 'all',
                'select' => [{getlang} => $link.id_page],
                'type' => 'menu'
                ]
                assign="pages"
                }
                {$pages = $pages[0].subdata}
            {elseif $link.type_link eq 'catalog'}
                {widget_catalog_data
                conf =[
                'context' => 'category',
                'select' => 'all'
                ]
                assign='pages'
                }
            {elseif $link.type_link eq 'category'}
                {widget_catalog_data
                conf =[
                'context' => 'category',
                'select' => [{getlang} => $link.id_page]
                ]
                assign='pages'
                }
            {elseif $link.type_link eq 'plugin'}
                {widget_menu_data conf=['type' => 'plugin', 'id' => $link.id_page, 'lang' => {getlang}]}
            {/if}
            {$links[$k]['subdata'] = $pages}
        {/strip}
    {/if}
{/foreach}
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
            Navigation
        </header>{/if}
        <nav id="{if $main}main{else}side{/if}-menu" class="menu menu-tabs-arrow menubar" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
            <a href="#content" class="sr-only skip-menu">{#skipMenu#}</a>
            <ul id="menul" class="list-unstyled">
                {include file="section/menu/loop/dropdown.tpl" menuData=$links mobile=$mobile}
            </ul>
        </nav>
        {if !$main}
        <footer>
            {include file="section/brick/sharebar.tpl"}
        </footer>
    </div>
    {/if}
</div>