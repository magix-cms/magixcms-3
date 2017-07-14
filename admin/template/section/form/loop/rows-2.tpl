{if isset($data) && !empty($data) && isset($section) && $section != ''}
    {foreach $data as $row}
    <tr id="{if $subcontroller}{$subcontroller}{else}{$controller}{/if}_{$row[$idcolumn]}">
        <td class="text-center">
            <div class="checkbox">
                <label for="{if $subcontroller}{$subcontroller}{else}{$controller}{/if}{$row[$idcolumn]}">
                    <input type="checkbox" id="{if $subcontroller}{$subcontroller}{else}{$controller}{/if}{$row[$idcolumn]}" name="{if $subcontroller}{$subcontroller}{else}{$controller}{/if}[]" value="{$row[$idcolumn]}"{if $row[$idcolumn]|in_array:$readonly} readonly disabled{/if}/>
                </label>
            </div>
        </td>
        {foreach $scheme as $name => $col}
            <td class="{$col.class}">
                {if $col.type == 'enum'}
                    {$text = $col.enum|cat:$row[$name]}
                    {#$text#}
                {elseif $col.type == 'bin'}
                    {if $row[$name]}<span class="fa fa-check text-success"></span>{else}<span class="fa fa-times text-danger"></span>{/if}
                {elseif $col.type == 'content'}
                    {if $row[$name]}{$row[$name]|truncate:100:'...'}{else}&mdash;{/if}
                {elseif $col.type == 'price'}
                    {if $row[$name]}{$row[$name]}&nbsp;<span class="fa fa-euro"></span>{elseif $row[$name] == null}&mdash;{else}{#price_0#|ucfirst}{/if}
                {elseif $col.type == 'date'}
                    {if $row[$name]}{$row[$name]|date_format:'%d/%m/%Y'}{else}&mdash;{/if}
                {else}
                    {if $row[$name]}{$row[$name]}{else}&mdash;{/if}
                {/if}
            </td>
        {/foreach}
        <td class="actions text-center">
            {if {employee_access type="edit" class_name=$cClass} eq 1}
            <a href="/{baseadmin}/index.php?controller={$controller}&action=edit&edit={$row[$idcolumn]}{if $subcontroller}&tabs={$subcontroller}{/if}" class="btn btn-link action_on_record"><span class="fa fa-pencil-square-o"></span></a>
            {/if}
            {if {employee_access type="del" class_name=$cClass} eq 1}
            {if !$row[$idcolumn]|in_array:$readonly}
                <a href="#" class="btn btn-link action_on_record modal_action" data-id="{$row[$idcolumn]}" data-controller="{$controller}" {if $subcontroller} data-sub="{$subcontroller}"{/if} data-target="#delete_modal"><span class="fa fa-trash"></span></a>
            {/if}
            {/if}
        </td>
    </tr>
    {/foreach}
{else}
    {include file="section/form/loop/no-record.tpl" col=(count($scheme) + 2)}
{/if}