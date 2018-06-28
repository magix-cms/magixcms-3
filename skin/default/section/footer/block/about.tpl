<div id="block-about" class="col block">
    <div>
        {widget_about_data
        conf = [
        'context' => $context,
        'type' => 'menu'
        ]
        assign="pages"
        }
        <h4><a href="{geturl}/{getlang}/about/" title="{#about#} {#website_name#}">{#about#|ucfirst}</a></h4>
        <ul class="link-list list-unstyled">
            {foreach $pages as $child}
                <li>
                    <a href="{geturl}{$child.url}" title="{$child.title|ucfirst}">{$child.title|ucfirst}</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>