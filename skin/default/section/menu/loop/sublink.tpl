{foreach $childs as $child}
{if $child.name}{$child.title = $child.name}{/if}
{if isset($child.controller)}{$chc = $child.controller}{/if}
{if ($chc === $active_link.controller || $chc === null ) && (in_array($child.id,$active_link.ids) || ($chc === null && !isset($child.id) && empty($active_link.ids)))}{$child.active = true}{/if}
{if $amp}
    <section>
        {if !{strpos($child.url_link,'amp')} && $child.amp_available}{$child.url_link = {$child.url_link|replace:{'/'|cat:{$lang}|cat:'/'}:{'/'|cat:{$lang}|cat:'/amp/'}}}{/if}
        {if $mega && $child.subdata && $dp < $deepness}
            {$sn = $sn + 1}
            {$navparent = "subnav-"|cat:$sn}
            <header>
                <a itemprop="url" href="{$child.url}" title="{$child.seo.title}"><span>{$child.title}</span></a>
                <span class="show-more"><i class="material-icons ico ico-add"></i></span>
                <span class="show-less"><i class="material-icons ico ico-remove"></i></span>
            </header>
            <div class="nested-accordion">
                <amp-accordion class="list-unstyled" animate expand-single-section disable-session-states>
                    {include file="section/menu/loop/sublink.tpl" scope="global" parent=$navparent childs=$child.subdata mega=$mega mobile=$mobile dp=($dp+1) amp=true}
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
    <li class="panel{if $child.active} active{/if}" itemprop="name">
        {if $mega && $child.subdata}
            {$sn = $sn + 1}
            {$navparent = "subnav-"|cat:$sn}
            {if $dp < $deepness}
            <button type="button" class="navbar-toggle{if $child.active} open{else} collapsed{/if}" data-toggle="collapse" data-parent="{$navparent}" data-target="#nav{$menu}-{$sn}">
                <span class="show-more"><i class="material-icons ico ico-add"></i></span>
                <span class="show-less"><i class="material-icons ico ico-remove"></i></span>
            </button>
            {/if}
        {/if}
        <a itemprop="url" href="{$child.url}" title="{$child.seo.title}"{if $child.subdata} class="has-dropdown"{/if}><span>{$child.title}</span></a>
        {if $mega && $child.subdata && $dp < $deepness}
            <nav id="nav{$menu}-{$sn}" class="collapse navbar-collapse{if $child.active} in{/if} dropdown">
                <ul id="subnav-{$sn}" class="list-unstyled" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
                    {include file="section/menu/loop/sublink.tpl" scope="global" navparent=$navparent childs=$child.subdata mega=$mega mobile=$mobile dp=($dp+1)}
                    {$sn = $sn}
                </ul>
            </nav>
        {/if}
    </li>
{/if}
{/foreach}