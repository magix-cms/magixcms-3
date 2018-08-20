{if !isset($editableRow)}
    {$editableRow = true}
{/if}
{if !isset($seeRecord)}
    {$seeRecord = false}
{/if}
<tr id="{$sub}_{$id}">
    {$content}
    <td class="actions text-right">
        {if $editableRow}
        <a class="btn btn-link action_on_record" role="button" data-toggle="collapse" href="#{$sub}Form{$id}" aria-expanded="false" aria-controls="{$sub}Form{$id}">
            <span class="fa fa-plus"></span>
        </a>
        {/if}
        {if $seeRecord}
        <a class="btn btn-link action_on_record" href="{$url}/{$recordController}.php?action=edit&edit={$id_record}">
            <span class="fa fa-eye"></span>
        </a>
        {/if}
        <a href="#" class="btn btn-link action_on_record modal_action" data-id="{$id}" data-target="#delete_modal" data-controller="{$controller}" data-sub="{$sub}">
            <span class="fa fa-trash"></span>
        </a>
    </td>
</tr>
{if $editableRow}
<tr class="collapse" id="{$sub}Form{$id}">
    <td colspan="4">
        <form action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&tabs={$sub}&edit={$id}" method="post" class="validate_form edit_in_list edit_loc">
            {include file="{$controller}/form/{$sub}.tpl"}
        </form>
    </td>
</tr>
{/if}