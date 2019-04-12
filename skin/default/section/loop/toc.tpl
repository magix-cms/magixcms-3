{if !isset($s)}
    {$s = 0}
{else}
    {$s = $s + 1}
{/if}
{foreach $pages as $child}
    {if in_array($child.id,$active_link.ids)}{$current_page = true}{else}{$current_page = false}{/if}
    {if $amp}
    <section>
    {if $child.subdata}
        <header class="dropdown-header{if $current_page} active{/if}">
            <div>
            <a href="{$child.url}" title="{$child.description}">{$child.name}</a>
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#s-{$s}-{$child@index}">
                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
            </button>
            </div>
        </header>
        <div class="submenu">
            <amp-accordion class="dropdown-menu" disable-session-states>
            {include file="section/loop/toc.tpl" pages=$child.subdata s=$s amp=true}
            </amp-accordion>
        </div>
    {else}
        <header{if $current_page} class="active"{/if}>
            <a href="{$child.url}" title="{$child.seo.description}">{$child.name}</a>
        </header>
        <div></div>
    {/if}
    </section>
    {else}
    {if $child.subdata}
        <li class="dropdown-header{if $current_page} active{/if}">
            <a href="{$child.url}" title="{$child.description}">{$child.name}</a>
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#s-{$s}-{$child@index}">
                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
            </button>
        </li>
        <li class="submenu">
            <ul id="s-{$s}-{$child@index}" class="collapse dropdown-menu">
                {include file="section/loop/toc.tpl" pages=$child.subdata s=$s}
            </ul>
        </li>
    {else}
        <li{if $current_page} class="active"{/if}>
            {*<i class="material-icons">lens</i>*}
            <a href="{$child.url}" title="{$child.seo.description}">{$child.name}</a>
        </li>
    {/if}
    {/if}
{/foreach}