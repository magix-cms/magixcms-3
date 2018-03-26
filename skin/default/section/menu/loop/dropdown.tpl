{if $menuData}
    {foreach $menuData as $item}
        {$current_controller = ($item.controller === $smarty.get.controller)}
        {$current_page = ($item.type_link === 'plugin' || $item.id_page === null || $item.id_page == $smarty.get.id || $item.id_page == $smarty.get.id_parent)}
        <li class="panel{if $current_controller && $current_page} active{/if}">
            {if $mobile && $item.subdata}
                <button type="button" class="navbar-toggle{if $item.active} open{/if}" data-toggle="collapse" data-parent="#menul" data-target="#nav-{$menu}-{$item@index}">
                    <span class="show-more"><i class="material-icons">more_vert</i></span>
                    <span class="show-less"><i class="material-icons">close</i></span>
                </button>
            {/if}
            <a itemprop="url_link" href="{$item.url_link}" title="{if empty($item.title_link)}{$item.name_link}{else}{$item.title_link}{/if}"{if $item.subdata} class="has-dropdown"{/if}>
                <span itemprop="name">{$item.name_link}</span>
            </a>
            {if !$mobile && $item.subdata}
                <ul class="dropdown" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
                    {foreach $item.subdata as $child}
                        {if $child.name}{$child.title = $child.name}{/if}
                        <li{if $child.url === $smarty.server.REQUEST_URI} class="active"{/if} itemprop="name">
                            <a itemprop="url_link" href="{$child.url}" title="{$child.title}"{if $child.subdata} class="has-dropdown"{/if}><span>{$child.title}</span></a>
                            {if $item.mode_link eq 'mega' && $child.subdata}
                                <ul class="dropdown" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
                                    {foreach $child.subdata as $ch}
                                        {if $ch.name}{$ch.title = $ch.name}{/if}
                                        <li{if $ch.url === $smarty.server.REQUEST_URI} class="active"{/if} itemprop="name"{if $child.active} class="active"{/if}>
                                            <a itemprop="url_link" href="{$ch.url}" title="{$ch.title}"><span>{$ch.title}</span></a>
                                        </li>
                                    {/foreach}
                                </ul>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
            {/if}
            {if $mobile && $item.subdata}
                <nav id="nav-{$menu}-{$item@index}" class="collapse navbar-collapse{if $item.active} in{/if}">
                    {*{$item.subdata|var_dump}*}
                    <ul id="subnav-ul-{$item@index}" class="list-unstyled">
                        {foreach $item.subdata as $child}
                            {if $child.name}{$child.title = $child.name}{/if}
                            <li class="panel{if $child.url === $smarty.server.REQUEST_URI} active{/if}" >
                                <a href="{$child.url}" title="{$child.title}"><span>{$child.title}</span></a>
                                {if $item.mode_link eq 'mega' && $child.subdata}
                                    <button type="button" class="navbar-toggle{if $item.active} open{/if}" data-toggle="collapse" data-parent="#subnav-ul-{$child@index}" data-target="#subnav-{$menu}-{$item@index}">
                                        <span class="show-more"><i class="material-icons">more_vert</i></span>
                                        <span class="show-less"><i class="material-icons">close</i></span>
                                    </button>
                                    <nav id="subnav-{$menu}-{$item@index}" class="collapse navbar-collapse{if $item.active} in{/if}">
                                        <ul class="list-unstyled">
                                            {foreach $child.subdata as $ch}
                                                {if $ch.name}{$ch.title = $ch.name}{/if}
                                                <li{if $ch.url === $smarty.server.REQUEST_URI} class="active"{/if}>
                                                    <a href="{$ch.url}" title="{$ch.title}"><span>{$ch.title}</span></a>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </nav>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </nav>
            {/if}
        </li>
    {/foreach}
{/if}