<div id="block-sitemap" class="col col-xs-2 col-sm block">
    <p class="h4"><a href="{$url}/{$lang}/" title="">{#site_navigation#|ucfirst}</a></p>
    <ul class="link-list list-unstyled">
        {foreach $links as $k => $link}
        <li>
            <a href="{$link.url_link}" title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}">
                <span>{$link.name_link}</span>
            </a>
        </li>
        {/foreach}
    </ul>
</div>