<div id="block-catalog" class="col-ph block">
    <p class="h4"><a href="{$url}/{$lang}/catalog/" title="{#catalog#|ucfirst}">{#catalog#|ucfirst}</a></p>
    <ul class="link-list list-unstyled">
        {widget_catalog_data
            conf =[
                'context' => 'category',
                'select' => 'all'
                ]
            assign='categoryList'
        }
        {foreach $categoryList as $category}
        <li><a href="{$category.url}" title="{$category.name}">{$category.name}</a></li>
        {/foreach}
    </ul>
</div>