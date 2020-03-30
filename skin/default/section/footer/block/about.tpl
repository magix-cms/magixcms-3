<div id="block-about" class="col col-xs-2 col-sm block">
    <div>
        {widget_about_data
            conf = [
                'context' => 'all',
                'type' => 'menu'
                ]
            assign="pages"
        }
        <p class="h4"><a href="{$url}/{$lang}/about/" title="{#about#} {#website_name#}">{#about_footer#|ucfirst}</a></p>
        <ul class="link-list list-unstyled">
            {foreach $pages as $child}
            <li>
                <a href="{$url}{$child.url}" title="{$child.seo.description}">{$child.name}</a>
            </li>
            {/foreach}
        </ul>
    </div>
</div>