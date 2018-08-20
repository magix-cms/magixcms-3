{if $menuData}
    {assign var="sn" value=0 scope="global"}
    {foreach $menuData as $k => $item}
        {$current_controller = ($item.controller === $smarty.get.controller)}
        {$current_page = ($item.type_link === 'plugin' || $item.id_page === null || $item.id_page == $smarty.get.id || $item.id_page == $smarty.get.id_parent || $item.id_page == $current.id_parent)}
        <li class="panel{if $current_controller && $current_page} active{/if}">
            {if $mobile && $item.subdata}
                {$sn = $sn + 1}
                <button type="button" class="navbar-toggle{if $item.active} open{/if}" data-toggle="collapse" data-parent="#menul" data-target="#nav{$menu}-{$sn}">
                    <span class="show-more"><i class="material-icons">more_vert</i></span>
                    <span class="show-less"><i class="material-icons">close</i></span>
                </button>
            {/if}
            <a itemprop="url_link" href="{$item.url_link}" title="{if empty($item.title_link)}{$item.name_link}{else}{$item.title_link}{/if}"{if $item.subdata} class="has-dropdown"{/if}>
                <span itemprop="name">{$item.name_link}</span>
            </a>
            {*{$item|var_dump}*}
            {if !$mobile && $item.subdata}
                <ul class="{if $item.mode_link eq 'mega'}mega{/if}dropdown" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
                    {if $item.mode_link === 'mega'}{$mega = true}{else}{$mega = false}{/if}
                    {include file="section/menu/loop/sublink.tpl" scope="global" childs=$item.subdata mega=$mega mobile=false}
                </ul>
            {/if}
            {if $mobile && $item.subdata}
                <nav id="nav{$menu}-{$sn}" class="collapse navbar-collapse{if $item.active} in{/if}">
                    <ul id="subnav-{$sn}" class="list-unstyled">
                        {if $item.mode_link === 'mega'}{$mega = true}{else}{$mega = false}{/if}
                        {include file="section/menu/loop/sublink.tpl" scope="global" childs=$item.subdata mega=$mega parent="subnav-{$sn}" mobile=true}
                        {$sn = $sn}
                    </ul>
                </nav>
            {/if}
        </li>
    {/foreach}
{/if}