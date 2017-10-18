{strip}
    {if isset($row)}
        {$similar = $row}
    {/if}
    {capture name="content"}
        <td>{$similar.id_rel}</td>
        <td>{$similar.name_p}</td>
    {/capture}
{/strip}
{include file="section/form/loop/list-rows.tpl" controller="product" sub="similar" content=$smarty.capture.content idc=$id id=$similar.id_rel editableRow=false}