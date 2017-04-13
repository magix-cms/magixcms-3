{if isset($data) && !empty($data)}
    <tr id="order_{$data['id_role']}">
        <td class="text-center">
            <div class="checkbox">
                <label for="role{$data['id_role']}">
                    <input type="checkbox" id="role{$data['id_role']}" name="role[]" value="{$data['id_role']}"/>
                </label>
            </div>
        </td>
        <td class="text-center">
            {$data['id_role']}
        </td>
        <td>
            {$data['role_name']}
        </td>
        <td class="actions text-center">
            <a href="{$smarty.server.SCRIPT_NAME}?action=edit&edit={$data['id_role']}" class="btn btn-link action_on_record"><span class="fa fa-pencil-square-o"></span></a>
            <a href="#" class="btn btn-link action_on_record modal_action" data-id="{$data['id_role']}" data-controller="role" data-target="#delete_modal"><span class="fa fa-trash"></span></a>
        </td>
    </tr>
{/if}