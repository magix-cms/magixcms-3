<ul{if isset($ulClass)} class="{$ulClass}" {/if}>
{foreach $cats as $cat}
    <li>
        <input type="checkbox" name="parent[{$page.id_product}]" id="cat_{$cat.id_cat}" value="{$cat.id_parent}"/>
        <label for="cat_{$cat.id_cat}">
            {if $cat.parent_id != NULL}
            <a class="tree-toggle" href="#csc-{$cat.id_cat}" data-id="{$cat.id_cat}" data-edit="{$page.id_product}"><span class="fa fa-folder"></span></a>
            {/if}
            {$cat.name_cat}
        </label>
        <div class="pull-right">
            <input type="radio" name="default[{$page.id_product}]" id="default_{$cat.id_cat}" value="{$cat.id_parent}" title="Choisir comme catégorie par défaut"/>
            <label for="default_{$cat.id_cat}"></label>
        </div>
        {if $cat.parent_id != NULL}
        <div id="csc-{$cat.id_cat}" class="cat-tree collapse"></div>
        {/if}
    </li>
{/foreach}
</ul>