{strip}{if $type eq 'head'}
{if is_array($data) && !empty($data)}
<link rel="canonical" href="{geturl}{$smarty.server.REQUEST_URI|replace:'amp/':''}">
{if is_null($smarty.get.controller)}<link rel="alternate" href="{geturl}amp/" hreflang="x-default" />{/if}
{foreach $data as $k => $lang}
{if isset($hreflang) && is_array($hreflang)}
{if isset($hreflang[$lang.id_lang])}
{$data[$k]['url'] = "{geturl}{$hreflang[$lang.id_lang]|replace:{'/'|cat:{$lang.iso_lang}|cat:'/'}:{'/'|cat:{$lang.iso_lang}|cat:'/amp/'}}"}
{/if}
{else}
{$data[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/"}
{/if}
{if !empty($data[$k]['url'])}
<link rel="alternate" href="{$data[$k]['url']}" hreflang="{$lang.iso_lang}" />
{/if}
{/foreach}
{/if}{/strip}
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
                                {foreach $data as $k => $lang}
                                    {if isset($hreflang) && is_array($hreflang) && isset($hreflang[$lang.id_lang])}
                                        {$data[$k]['url'] = "{geturl}{$hreflang[$lang.id_lang]|replace:{'/'|cat:{$lang.iso_lang}|cat:'/'}:{'/'|cat:{$lang.iso_lang}|cat:'/amp/'}}"}
                                    {else}
                                        {$data[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/"}
                                    {/if}
                                    <li>
                                        <a href="{$data[$k]['url']}">{$lang.iso_lang|upper}</a>
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