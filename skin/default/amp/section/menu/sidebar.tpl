<amp-sidebar id="sidebar1" layout="nodisplay" side="left">
    <div class="sidebar">
        <header>
            <div role="button" aria-label="close sidebar" on="tap:sidebar1.toggle" tabindex="0" class="close-sidebar"><i class="material-icons">close</i></div>
            Navigation
        </header>
        {widget_menu_data lang={$lang}}
        <ul class="menu list-unstyled">
        {foreach $links as $link}
            {if !{$link.url_link|strpos:'amp'}}{$link.url_link = {$link.url_link|replace:{'/'|cat:{$lang}|cat:'/'}:{'/'|cat:{$lang}|cat:'/amp/'}}}{/if}
            <li>
                {if $link.mode_link eq 'simple'}
                <a href="{$link.url_link}"
                   title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}">
                    {$link.name_link}
                </a>
                {else}
                {strip}
                    {if $link.type_link eq 'home'}
                    {if $link.mode_link eq 'dropdown'}{$context = 'parent'}{else}{$context = 'all'}{/if}
                    {widget_cms_data
                        conf = [
                            'context' => $context,
                            'type' => 'menu'
                            ]
                        assign="pages"
                    }
                    {elseif $link.type_link eq 'about'}
                    {if $link.mode_link eq 'dropdown'}{$context = 'parent'}{else}{$context = 'all'}{/if}
                    {widget_about_data
                        conf = [
                            'context' => $context,
                            'type' => 'menu'
                            ]
                        assign="pages"
                    }
                    {elseif $link.type_link eq 'about_page'}
                    {if $link.mode_link eq 'dropdown'}{$context = 'child'}{else}{$context = 'all'}{/if}
                    {widget_about_data
                        conf = [
                            'context' => $context,
                            'select' => [{$lang} => $link.id_page],
                            'type' => 'menu'
                            ]
                        assign="pages"
                    }
                    {$pages = $pages[0].subdata}
                    {elseif $link.type_link eq 'pages'}
                    {if $link.mode_link eq 'dropdown'}{$context = 'child'}{else}{$context = 'all'}{/if}
                    {widget_cms_data
                        conf = [
                            'context' => $context,
                            'select' => [{$lang} => $link.id_page],
                            'type' => 'menu'
                            ]
                        assign="pages"
                    }
                    {$pages = $pages[0].subdata}
                    {elseif $link.type_link eq 'catalog'}
                    {widget_catalog_data
                        conf =[
                            'context' => 'category',
                            'select' => 'all'
                            ]
                        assign='pages'
                    }
                    {elseif $link.type_link eq 'category'}
                    {/if}
                {/strip}
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
                        {if $link.mode_link eq 'dropdown'}
                        <div class="nested-accordion">
                            <ul class="list-unstyled">
                            {foreach $pages as $p}
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
                        {else}
                        <amp-accordion class="nested-accordion">
                        {foreach $pages as $p}
                            {if $p.name}{$p.title = $p.name}{/if}
                            <section>
                                <header>
                                    <a href="{$p.url}"
                                       title="{$p.title}">
                                        {$p.title}
                                    </a>
                                    <span class="show-more"><i class="material-icons">more_vert</i></span>
                                    <span class="show-less"><i class="material-icons">close</i></span>
                                </header>
                                <div>
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
                        {/foreach}
                        </amp-accordion>
                        {/if}
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