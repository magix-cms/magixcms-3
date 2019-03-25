{if $menuData}
    {assign var="sn" value=0 scope="global"}
    {foreach $menuData as $k => $item}
        {assign var="dp" value=0}
        {if $item.controller === $active_link.controller && (!isset($item.id_page) || in_array($item.id_page,$active_link.ids))}{$item.active = true}{/if}
        <li class="panel{if $item.active} active{/if}">
            {if $item.mode_link === 'mega'}{$mega = true}{else}{$mega = false}{/if}
            {if $mobile && $item.subdata}
                {$sn = $sn + 1}
                <button type="button" class="navbar-toggle{if $item.active} open{else} collapsed{/if}" data-toggle="collapse" data-parent="#menul" data-target="#nav{$menu}-{$sn}">
                    <span class="show-more"><i class="material-icons">add</i></span>
                    <span class="show-less"><i class="material-icons">remove</i></span>
                </button>
            {/if}
            <a itemprop="url" href="{$item.url_link}" title="{if empty($item.title_link)}{$item.name_link}{else}{$item.title_link}{/if}"{if $item.subdata} class="has-dropdown"{/if}>
                <span itemprop="name">{$item.name_link}</span>
            </a>
            {if !$mobile && $item.subdata}
                <ul class="{if $item.mode_link eq 'mega'}mega{/if}dropdown" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
                    {include file="section/menu/loop/sublink.tpl" scope="global" childs=$item.subdata mega=$mega mobile=false dp=($dp+1) chc=$item.controller}
                </ul>
            {/if}
            {if $mobile && $item.subdata}
                <nav id="nav{$menu}-{$sn}" class="collapse navbar-collapse{if $item.active} in{/if}">
                    <ul id="subnav-{$sn}" class="list-unstyled">
                        {if $item.mode_link === 'mega'}{$mega = true}{else}{$mega = false}{/if}
                        {include file="section/menu/loop/sublink.tpl" scope="global" childs=$item.subdata mega=$mega parent="subnav-{$sn}" mobile=true dp=($dp+1) chc=$item.controller}
                        {$sn = $sn}
                    </ul>
                </nav>
            {/if}
        </li>
    {/foreach}
{/if}