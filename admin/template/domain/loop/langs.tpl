{strip}
    {if isset($row)}
        {$langs = $row}
    {/if}
    {capture name="content"}
        <td>{$langs.id_domain_lg}</td>
        <td>{$langs.name_lang}</td>
    {/capture}
{/strip}
{include file="section/form/loop/list-rows.tpl" controller="domain" sub="langs" content=$smarty.capture.content idc=$id id=$langs.id_domain_lg editableRow=false}