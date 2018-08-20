{foreach $childs as $child}
{if $child.name}{$child.title = $child.name}{/if}
{if $child.url === $smarty.server.REQUEST_URI}{$child.active = true}{/if}
{if $mobile}
<li class="panel{if $child.active} active{/if}">
    {if $mega && $child.subdata}
        {$sn = $sn + 1}
        <button type="button" class="navbar-toggle{if $child.active} open{/if}" data-toggle="collapse" data-parent="{$parent}" data-target="#nav{$menu}-{$sn}">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
    {/if}
    <a href="{$child.url}" title="{$child.title}"><span>{$child.title}</span></a>
    {if $mega && $child.subdata}
        <nav id="nav{$menu}-{$sn}" class="collapse navbar-collapse{if $child.active} in{/if}">
            <ul id="subnav-{$sn}" class="list-unstyled">
                {include file="section/menu/loop/sublink.tpl" scope="global" parent="subnav-{$sn}" childs=$child.subdata mega=$mega mobile=$mobile}
                {$sn = $sn}
            </ul>
        </nav>
    {/if}
</li>
{else}
<li{if $child.url === $smarty.server.REQUEST_URI} class="active"{/if} itemprop="name">
    <a itemprop="url_link" href="{$child.url}" title="{$child.title}"{if $child.subdata} class="has-dropdown"{/if}><span>{$child.title}</span></a>
    {if $mega && $child.subdata}
        <ul class="dropdown" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
            {include file="section/menu/loop/sublink.tpl" childs=$child.subdata mega=$mega mobile=$mobile}
        </ul>
    {/if}
</li>
{/if}
{/foreach}