{foreach $childs as $child}
{if $child.name}{$child.title = $child.name}{/if}
{if isset($child.controller)}{$chc = $child.controller}{/if}
{if $chc === $active_link.controller && (in_array($child.id,$active_link.ids) || !isset($child.id) && empty($active_link.ids))}{$child.active = true}{/if}
{if $amp}
    <section>
        {if !{$child.url_link|strpos:'amp'} && $child.amp_available}{$child.url_link = {$child.url_link|replace:{'/'|cat:{$lang}|cat:'/'}:{'/'|cat:{$lang}|cat:'/amp/'}}}{/if}
        {if $mega && $child.subdata && $dp < $deepness}
            {$sn = $sn + 1}
            <header>
                <a itemprop="url" href="{$child.url}" title="{$child.seo.title}"><span>{$child.title}</span></a>
                <span class="show-more"><i class="material-icons">add</i></span>
                <span class="show-less"><i class="material-icons">remove</i></span>
            </header>
            <div class="nested-accordion">
                <amp-accordion class="list-unstyled" animate expand-single-section disable-session-states>
                    {include file="section/menu/loop/sublink.tpl" scope="global" parent="subnav-{$sn}" childs=$child.subdata mega=$mega mobile=$mobile dp=($dp+1) amp=true}
                    {$sn = $sn}
                </amp-accordion>
            </div>
        {else}
            <header>
                <a itemprop="url" href="{$child.url}" title="{$child.seo.title}"><span>{$child.title}</span></a>
            </header>
            <div></div>
        {/if}
    </section>
{else}
{if $mobile}
<li class="panel{if $child.active} active{/if}">
    {if $mega && $child.subdata}
        {$sn = $sn + 1}
        {if $dp < $deepness}
        <button type="button" class="navbar-toggle{if $child.active} open{else} collapsed{/if}" data-toggle="collapse" data-parent="{$parent}" data-target="#nav{$menu}-{$sn}">
            <span class="show-more"><i class="material-icons">add</i></span>
            <span class="show-less"><i class="material-icons">remove</i></span>
        </button>
        {/if}
    {/if}
    <a href="{$child.url}" title="{$child.seo.title}"><span>{$child.title}</span></a>
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
    <a itemprop="url" href="{$child.url}" title="{$child.seo.title}"{if $child.subdata} class="has-dropdown"{/if}><span>{$child.title}</span></a>
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
{/if}
{/foreach}