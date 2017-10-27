<div id="block-sitemap" class="col-ph-12 col-sm-3 col-lg-2 block">
    <h4><a href="{geturl}/{getlang}/" title="">{#site_navigation#|ucfirst}</a></h4>
    <ul class="link-list list-unstyled">
        <li>
            <a href="{geturl}/{getlang}/" title="">Accueil</a>
        </li>
        {widget_cms_data
        conf = [
        'select' => [{getlang} => {#menu_pages#}],
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
            <a href="{geturl}/{getlang}/{#nav_catalog_uri#}/" title="{#catalog#|ucfirst}">{#catalog#|ucfirst}</a>
        </li>
        {widget_cms_data
        conf = [
        'select' => [{getlang} => {#menu_pages_2#}],
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
            <a href="{geturl}/{getlang}/{#nav_news_uri#}/" title="{#show_news#|ucfirst}">{#news#|ucfirst}</a>
        </li>
        <li>
            <a href="{geturl}/{getlang}/contact/" title="{#show_contact_form#|ucfirst}">{#contact#|ucfirst}</a>
        </li>
    </ul>
</div>