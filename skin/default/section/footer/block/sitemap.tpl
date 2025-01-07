<div id="block-sitemap" class="col-ph col-xs-6 col-sm-3 block">
    <p class="h4"><a href="{if $dataLang != null && count($dataLang) > 1}{$url}/{$lang}/{else}{$url}/{/if}" title="">{#site_navigation#|ucfirst}</a></p>
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