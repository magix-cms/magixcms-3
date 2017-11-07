{if $menuData}
    {foreach $menuData as $item}
        <li class="panel{if $item.active} active{/if}">
            {if $mobile && $item.subdata}
                <button type="button" class="navbar-toggle{if $item.active} open{/if}" data-toggle="collapse" data-parent="#menul" data-target="#nav-{$menu}-{$item@index}">
                    <span class="show-more"><i class="material-icons">more_vert</i></span>
                    <span class="show-less"><i class="material-icons">close</i></span>
                </button>
            {/if}
            <a itemprop="url_link" href="{$item.url_link}" title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}"{if $item.subdata} class="has-dropdown"{/if}>
                <span itemprop="name">{$item.name_link}</span>
            </a>
            {if !$mobile && $item.subdata}
                <ul class="dropdown hidden-xs"{if $microData} itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement"{/if}>
                    {foreach $item.subdata as $child}
                        <li{if $child.active} class="active"{/if} itemprop="name"><a itemprop="url_link" href="{$child.url_link}" title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}"><span>{$child.name_link}</span></a></li>
                    {/foreach}
                </ul>
            {/if}
            {if $mobile && $item.subdata}
                <nav id="nav-{$menu}-{$item@index}" class="collapse navbar-collapse{if $item.active} in{/if}">
                    {*{$item.subdata|var_dump}*}
                    <ul id="subnav-ul-{$item@index}" class="list-unstyled">
                        {foreach $item.subdata as $child}
                            {if $child.name}{$child.title = $child.name}{/if}
                            <li class="panel{if $ch.active} active{/if}" >
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
                                                <li{if $child.active} class="active"{/if}>
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