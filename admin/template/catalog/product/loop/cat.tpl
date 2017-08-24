<ul{if isset($ulClass)} class="{$ulClass}" {/if}>
{foreach $cats as $cat}
    {strip}{$selected = false}
    {$default = false}
    {if array_key_exists($cat.id_cat, $catRel)}
        {$selected = true}
        {if $catRel[$cat.id_cat]['default_c']}
            {$default = true}
        {/if}
    {/if}{/strip}
    <li>
        <input type="checkbox" name="parent[{$cat.id_cat}]" id="cat_{$cat.id_cat}"{if $selected} checked{/if}/>
        <label for="cat_{$cat.id_cat}">
            {if isset($cat.subcat)}
            <a class="tree-toggle" href="#csc-{$cat.id_cat}" data-id="{$cat.id_cat}" data-edit="{$page.id_product}"><span class="fa fa-folder"></span></a>
            {/if}
            {$cat.name_cat}
        </label>
        <div class="pull-right">
            <input type="radio" name="default_cat" id="default_{$cat.id_cat}" value="{$cat.id_cat}" title="Choisir comme catégorie par défaut"{if $default} checked{else}{if !$selected} disabled{/if}{/if}/>
            <label for="default_{$cat.id_cat}"></label>
        </div>
        {if isset($cat.subcat)}
        <div id="csc-{$cat.id_cat}" class="cat-tree collapse">
            {include file="catalog/product/loop/cat.tpl" cats=$cat.subcat}
        </div>
        {/if}
    </li>
{/foreach}
</ul>