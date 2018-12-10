<div id="block-sitemap" class="col block">
    <h4><a href="{$url}/{$lang}/amp/" title="">{#site_navigation#|ucfirst}</a></h4>
    <ul class="link-list list-unstyled">
        {*<li>
            <a href="{$url}/{$lang}/" title="">Accueil</a>
        </li>
        {widget_cms_data
        conf = [
        'select' => [{$lang} => {#menu_pages#}],
        'context' => 'parent'
        ]
        assign="pages"
        }
        {foreach $pages as $page}
            <li>
                <a href="{$page.url}/" title="{$page.name}">{$page.name}</a>
            </li>
        {/foreach}
        <li>
            <a href="{$url}/{$lang}/{#nav_catalog_uri#}/" title="{#catalog#|ucfirst}">{#catalog#|ucfirst}</a>
        </li>
        {widget_cms_data
        conf = [
        'select' => [{$lang} => {#menu_pages_2#}],
        'context' => 'parent'
        ]
        assign="pages"
        }
        {foreach $pages as $page}
            <li>
                <a href="{$page.url}/" title="{$page.name}">{$page.name}</a>
            </li>
        {/foreach}
        <li>
            <a href="{$url}/{$lang}/{#nav_news_uri#}/" title="{#show_news#|ucfirst}">{#news#|ucfirst}</a>
        </li>
        <li>
            <a href="{$url}/{$lang}/contact/" title="{#show_contact_form#|ucfirst}">{#contact#|ucfirst}</a>
        </li>*}
        {foreach $links as $k => $link}
            {if !{$link.url_link|strpos:'amp'}}{$link.url_link = {$link.url_link|replace:{'/'|cat:{$lang}|cat:'/'}:{'/'|cat:{$lang}|cat:'/amp/'}}}{/if}
            <li>
                <a href="{$link.url_link}" title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}">
                    <span>{$link.name_link}</span>
                </a>
            </li>
        {/foreach}
    </ul>
</div>