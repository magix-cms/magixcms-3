<div id="block-cms" class="col-ph-12 col-sm-6 col-md-3 block">
    <h4><a href="{geturl}/{getlang}/{#nav_catalog_uri#}/" title="{#catalog#|ucfirst}">{#catalog#|ucfirst}</a></h4>
    <ul class="link-list list-unstyled">
        {widget_catalog_data
        conf = [
        'context' => 'category',
        'select' => [{getlang} => {#menu_catalog#}],
        'sort' => ['order' => 'ASC']
        ]
        assign="categoryList"
        }
        {foreach $categoryList as $category}
            <li><a href="{$category.url}" title="{$category.name}">{$category.name}</a></li>
        {/foreach}
    </ul>
</div>