<amp-sidebar id="sidebar1" layout="nodisplay" side="left">
    <div class="sidebar">
        <header>
            <div role="button" aria-label="close sidebar" on="tap:sidebar1.toggle" tabindex="0" class="close-sidebar"><i class="material-icons">close</i></div>
            Navigation
        </header>
        <ul class="menu list-unstyled">
        {foreach $links as $link}
            {if !{$link.url_link|strpos:'amp'}}{$link.url_link = {$link.url_link|replace:{'/'|cat:{$lang}|cat:'/'}:{'/'|cat:{$lang}|cat:'/amp/'}}}{/if}
            <li>
                {if $link.mode_link eq 'simple' || !isset($link.subdata) || empty($link.subdata)}
                <a href="{$link.url_link}"
                   title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}">
                    {$link.name_link}
                </a>
                {else}
                <amp-accordion{if $link.mode_link eq 'mega'} disable-session-states{/if}>
                    <section>
                        <header>
                            <a href="{$link.url_link}"
                               title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}">
                                {$link.name_link}
                            </a>
                            <span class="show-more"><i class="material-icons">more_vert</i></span>
                            <span class="show-less"><i class="material-icons">close</i></span>
                        </header>
                        <div class="nested-accordion">
                            <ul class="list-unstyled">
                                {foreach $link.subdata as $p}
                                    {if $p.name}{$p.title = $p.name}{/if}
                                    {if $link.mode_link eq 'mega' && $p.subdata}
                                    <amp-accordion>
                                        <section>
                                            <header>
                                                <a href="{$p.url}"
                                                   title="{$p.title}">
                                                    {$p.title}
                                                </a>
                                                <span class="show-more"><i class="material-icons">more_vert</i></span>
                                                <span class="show-less"><i class="material-icons">close</i></span>
                                            </header>
                                            <div class="nested-accordion">
                                                <ul class="list-unstyled">
                                                    {foreach $p.subdata as $p}
                                                        {if $p.name}{$p.title = $p.name}{/if}
                                                        <li>
                                                            <a href="{$p.url}"
                                                               title="{$p.title}">
                                                                {$p.title}
                                                            </a>
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </section>
                                    </amp-accordion>
                                    {else}
                                    <li>
                                        <a href="{$p.url}"
                                           title="{$p.title}">
                                            {$p.title}
                                        </a>
                                    </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        </div>
                    </section>
                </amp-accordion>
                {/if}
            </li>
        {/foreach}
        </ul>
        <footer>
            {include file="section/brick/sharebar.tpl"}
        </footer>
    </div>
</amp-sidebar>