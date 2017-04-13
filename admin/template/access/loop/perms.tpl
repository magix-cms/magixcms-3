{strip}
    {if isset($row)}
        {$access = $row}
    {/if}
    {capture name="content"}
        <td>{$access.id_access}</td>
        <td>{$access.name}</td>
        <td>{if $access.view eq '1'}<span class="fa fa-check"></span>{else}<span class="fa fa-remove"></span>{/if}</td>
        <td>{if $access.append eq '1'}<span class="fa fa-check"></span>{else}<span class="fa fa-remove"></span>{/if}</td>
        <td>{if $access.edit eq '1'}<span class="fa fa-check"></span>{else}<span class="fa fa-remove"></span>{/if}</td>
        <td>{if $access.del eq '1'}<span class="fa fa-check"></span>{else}<span class="fa fa-remove"></span>{/if}</td>
        <td>{if $access.action eq '1'}<span class="fa fa-check"></span>{else}<span class="fa fa-remove"></span>{/if}</td>
    {/capture}
{/strip}
{include file="section/form/loop/list-rows.tpl" controller="access" sub="perms" content=$smarty.capture.content idc=$id id=$access.id_access}