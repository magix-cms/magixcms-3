<div id="block-about" class="col-ph-12 col-lg-2 pull-lg-4 block">
    <div>
        {widget_cms_data
        conf = [
        'select' => [{getlang} => {#block_cms_pages#}],
        'context' => 'mix'
        ]
        assign="pages"
        }
        <h4><a href="{geturl}/{getlang}/about/" title="{#about_title#} {#website_name#}">{#about_label#|ucfirst}</a></h4>
        <ul class="list-unstyled">
            {foreach $about.childs as $child}
                <li>
                    <a href="{geturl}/{getlang}/about/{$child.uri}-{$child.id}/" title="{$child.title|ucfirst}">{$child.title|ucfirst}</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>