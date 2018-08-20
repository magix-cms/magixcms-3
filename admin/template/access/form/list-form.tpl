{if !isset($class_form)}{$class_form = "col-ph-12 col-md-6"}{/if}
{if !isset($class_table)}{$class_table = "col-ph-12 col-md-6"}{/if}
<h3>Ajouter un module</h3>
<div class="row">
    <form id="add_{$sub}" action="{$url}/{baseadmin}/index.php?controller=access&amp;action=add&tabs={$sub}&edit={$id}" method="post" class="validate_form add_to_list {$class_form}">
        {include file="{$controller}/form/{$sub}.tpl"}
    </form>
    <div class="{$class_table}">
        <div class="table-responsive">
            <table class="table table-condensed{if isset($customClass)} {$customClass}{/if}">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>{#view#}</th>
                    <th>{#add#}</th>
                    <th>{#edit#}</th>
                    <th>{#remove#}</th>
                    <th>{#operation#}</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="{$sub}List" class="direct-edit-table">
                {if !empty($data)}
                    {foreach $data as $row}
                        {include file="{$controller}/loop/{$sub}.tpl" first=$row@first}
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>