{foreach $childs as $child}
{if $child.name}{$child.title = $child.name}{/if}
{if isset($child.controller)}{$chc = $child.controller}{/if}
{if $chc === $active_link.controller && (in_array($child.id,$active_link.ids) || !isset($child.id) && empty($active_link.ids))}{$child.active = true}{/if}
{if $mobile}
<li class="panel{if $child.active} active{/if}">
    {if $mega && $child.subdata}
        {$sn = $sn + 1}
        {if $dp < $deepness}
        <button type="button" class="navbar-toggle{if $child.active} open{/if}" data-toggle="collapse" data-parent="{$parent}" data-target="#nav{$menu}-{$sn}">
            <span class="show-more"><i class="material-icons">more_vert</i></span>
            <span class="show-less"><i class="material-icons">close</i></span>
        </button>
        {/if}
    {/if}
    <a href="{$child.url}" title="{$child.title}"><span>{$child.title}</span></a>
    {if $mega && $child.subdata && $dp < $deepness}
        <nav id="nav{$menu}-{$sn}" class="collapse navbar-collapse{if $child.active} in{/if}">
            <ul id="subnav-{$sn}" class="list-unstyled">
                {include file="section/menu/loop/sublink.tpl" scope="global" parent="subnav-{$sn}" childs=$child.subdata mega=$mega mobile=$mobile dp=($dp+1)}
                {$sn = $sn}
            </ul>
        </nav>
    {/if}
</li>
{else}
<li{if $child.active} class="active"{/if} itemprop="name">
    <a itemprop="url" href="{$child.url}" title="{$child.title}"{if $child.subdata} class="has-dropdown"{/if}><span>{$child.title}</span></a>
    {if $mega && $child.subdata && $dp < $deepness}
        {$sn = $sn + 1}
        {if $dp < $deepness}
        <ul class="dropdown" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
            {include file="section/menu/loop/sublink.tpl" childs=$child.subdata mega=$mega mobile=$mobile dp=($dp+1)}
        </ul>
        {/if}
    {/if}
</li>
{/if}
{/foreach}