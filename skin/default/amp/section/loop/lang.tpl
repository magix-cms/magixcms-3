{if $type eq 'head'}
{if is_array($data) && !empty($data)}
   {foreach $data as $k => $lang}
        {if isset($hreflang) && is_array($hreflang) && isset($hreflang[$lang.id_lang])}
            {$data[$k]['url'] = "{geturl}{$hreflang[$lang.id_lang]|replace:{'/'|cat:{$lang.iso_lang}|cat:'/'}:{'/'|cat:{$lang.iso_lang}|cat:'/amp/'}}"}
        {else}
            {$data[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/"}
        {/if}
    {/foreach}
    <link rel="canonical" href="{geturl}{$smarty.server.REQUEST_URI|replace:'amp/':''}">
    {if is_null($smarty.get.controller)}
    <link rel="alternate" href="{geturl}amp/" hreflang="x-default" />
    {/if}
{foreach $data as $item}
    <link rel="alternate" href="{$item.url}" hreflang="{$item.iso_lang}" />
{/foreach}
{/if}
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