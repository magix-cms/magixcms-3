{if is_array($dataLang) && !empty($dataLang)}
    {if $display eq 'list'}
        <ul class="lang-nav list-inline">
            {foreach $dataLang as $item}
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
                                {$defaultLang.iso_lang|upper}
                            {/if}
                        </button>
                    </header>
                    <div>
                        <ul class="list-unstyled">
                            {foreach $dataLang as $k => $lang}
                                {if isset($hreflang) && is_array($hreflang) && isset($hreflang[$lang.id_lang])}
                                    {$dataLang[$k]['url'] = "{geturl}{$hreflang[$lang.id_lang]}"}
                                {else}
                                    {if isset($smarty.get.controller) && $smarty.get.controller !== 'home'}
                                        {$dataLang[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/{$smarty.get.controller}/"}
                                    {else}
                                        {$dataLang[$k]['url'] = "{geturl}/{$lang.iso_lang}/amp/"}
                                    {/if}
                                {/if}
                                <li>
                                    <a href="{$dataLang[$k]['url']}">{$lang.iso_lang|upper}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </section>
            </amp-accordion>
        </div>
    {/if}
{/if}