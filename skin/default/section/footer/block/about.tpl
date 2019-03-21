<div id="block-about" class="col-6 col-sm block">
    <div>
        {widget_about_data
            conf = [
            'context' => 'all',
            'type' => 'menu'
            ]
            assign="pages"
        }
        <h4><a href="{$url}/{$lang}/about/" title="{#about#} {#website_name#}">{#about_footer#|ucfirst}</a></h4>
        <ul class="link-list list-unstyled">
            {foreach $pages as $child}
                <li>
                    <a href="{$url}{$child.url}" title="{$child.seo.description}">{$child.name}</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>