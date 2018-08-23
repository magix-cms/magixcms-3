{if {#block_cms_pages#}}
    {widget_cms_data
    conf = [
    'select' => [{$lang} => {#block_cms_pages#}],
'context' => 'all'
]
assign="pages"
}
    {foreach $pages as $page}
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 block">
            <h4><a href="{$page.url}/" title="{$page.name}">{$page.name}</a></h4>
            <ul class="link-list list-unstyled">
                {foreach $page.subdata as $subp}
                    <li><a href="{$subp.url}/" title="{$subp.name}">{$subp.name}</a></li>
                {/foreach}
            </ul>
        </div>
    {/foreach}
{/if}