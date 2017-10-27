{if $menuData}
    {foreach $menuData as $item}
        <li class="panel{if $item.active} active{/if}">
            {if $mobile && $item.subdata}
                <button type="button" class="navbar-toggle{if $item.active} open{/if}" data-toggle="collapse" data-parent="#menu-ul" data-target="#nav-{$menu}-{$item@index}">
                    <i class="material-icons">add</i>
                </button>
            {/if}
            <a itemprop="url" href="{$item.url}" title="{$item.title|ucfirst}"{if $item.subdata} class="has-dropdown"{/if}>
                <span itemprop="name">{$item.name|ucfirst}</span>
            </a>
            {if !$mobile && $item.subdata}
                <ul class="dropdown hidden-xs"{if $microData} itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement"{/if}>
                    {foreach $item.subdata as $child}
                        <li{if $child.active} class="active"{/if} itemprop="name"><a itemprop="url" href="{$child.url}" title="{$child.title|ucfirst}"><span>{$child.name|ucfirst}</span></a></li>
                    {/foreach}
                </ul>
            {/if}
            {if $mobile && $item.subdata}
                <nav id="nav-{$menu}-{$item@index}" class="collapse navbar-collapse{if $item.active} in{/if}">
                    <ul>
                        {foreach $item.subdata as $child}
                            <li{if $child.active} class="active"{/if}>
                                <a href="{$child.url}" title="{$child.title|ucfirst}"><span>{$child.name|ucfirst}</span></a>
                            </li>
                        {/foreach}
                    </ul>
                </nav>
            {/if}
        </li>
    {/foreach}
{/if}