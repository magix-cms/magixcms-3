{if $type eq 'head'}
    {if is_array($data) && !empty($data)}
        {if $smarty.server.SCRIPT_NAME === '/index.php'}
            <link rel="alternate" href="{geturl}" hreflang="x-default" />
            {foreach $data as $item}
                <link rel="alternate" href="{geturl}/{$item.iso}/" hreflang="{$item.iso}" />
            {/foreach}
        {/if}
    {/if}
{elseif $type eq 'cannonical'}
    <link rel="canonical" href="{geturl}{$smarty.server.REQUEST_URI|replace:'amp/':''}" />
{elseif $type eq 'nav'}
    {if is_array($data) && !empty($data)}
        {if $display eq 'list'}
            <ul class="lang-nav list-inline">
                {foreach $data as $item}
                    <li>
                        <a{if $smarty.get.strLangue eq $item.iso} class="active"{/if} href="/{$item.iso}/" hreflang="{$item.iso}" title="{#go_to_version#|ucfirst}: {$item.language}">
                            {$item.iso|upper}
                        </a>
                    </li>
                {/foreach}
            </ul>
        {elseif $display eq 'menu'}
            <div class="dropdown">
                <amp-accordion disable-session-states>
                    <section>
                        <header>
                            <button class="btn btn-box btn-default" type="button">
                                <span class="show-more"><i class="material-icons">arrow_drop_down</i></span>
                                <span class="show-less"><i class="material-icons">arrow_drop_up</i></span>
                                {if $smarty.get.strLangue}
                                    {$smarty.get.strLangue|upper}
                                {else}
                                    {$defaultLang.iso|upper}
                                {/if}
                            </button>
                        </header>
                        <div>
                            <ul class="list-unstyled">
                                {foreach $data as $item}
                                    <li>
                                        <a{if (isset($smarty.get.strLangue) && $item.iso_lang eq $smarty.get.strLangue) || (!isset($smarty.get.strLangue) && $item.iso_lang eq $defaultLang.iso_lang)} class="active"{/if} href="/{$item.iso_lang}/amp/" hreflang="{$item.iso_lang}" title="{$item.name_lang}">
                                            {$item.iso_lang|upper}
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    </section>
                </amp-accordion>
            </div>
        {/if}
    {/if}
{/if}