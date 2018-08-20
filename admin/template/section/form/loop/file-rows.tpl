<tr id="document_{$value.id_doc}">
    <td>
        {#$value.type_doc#|ucfirst}
    </td>
    <td>
        {switch $value.id_lang}
            {case 1 break}
            {$lang = "lang_fr"}
            {case 2 break}
            {$lang = "lang_nl"}
        {/switch}
        {#$lang#|ucfirst}
    </td>
    <td class="date_modified">
        {$value.date_update_doc|date_format:"%d-%m-%Y"}
    </td>
    <td class="actions text-center">
        <a href="{$url}/upload/pdf/{$value.id_defunct}/{$value.name_doc}" class="btn btn-link targetblank">
            <span class="fa fa-eye"></span><span class="sr-only"> Afficher</span>
        </a>
        <a href="{$url}/document.php?action=edit&tabs=document&edit={$value.id_defunct}&download={$value.name_doc}" class="btn btn-link targetblank">
            <span class="fa fa-download"></span><span class="sr-only"> download</span>
        </a>
        {if {employee_access type="delete_access" class_name=$cClass} eq 1}
        <a href="#" class="btn btn-link action_on_record modal_action" data-id="{$value.id_doc}" data-controller="defunct" data-sub="document" data-target="#delete_modal">
            <span class="fa fa-trash"></span><span class="sr-only"> supprimer</span>
        </a>
        {/if}
    </td>
</tr>