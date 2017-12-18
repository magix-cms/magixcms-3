<div class="row">
    <div class="col-ph-12">
        {if !isset($row)}
        <div class="form-group">
            <label for="id_module">{#module#|ucfirst}</label>
            <select name="id_module" id="id_module" class="form-control required" required>
                <option value="" selected>{#ph_module#|ucfirst}</option>
                {foreach $module as $key}
                    <option value="{$key.id_module}"{if $access.id_module == $key.id_module} selected{/if}>{$key.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" id="selectAll" /> Select All
            </label>
        </div>
        {/if}
        <div class="form-group">
            <label class="checkbox-inline">
                <input type="checkbox" name="view" id="view" class="checkbox-access" value="1" {if $access && $access.view == '1'} checked{/if} /> {#view#}
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="append" id="append" class="checkbox-access" value="1" {if $access && $access.append == '1'} checked{/if} /> {#add#}
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="edit" id="edit" class="checkbox-access" value="1" {if $access && $access.edit == '1'} checked{/if} /> {#edit#}
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="del" id="del" class="checkbox-access" value="1" {if $access && $access.del == '1'} checked{/if} /> {#remove#}
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" name="action" id="action" class="checkbox-access" value="1" {if $access && $access.action == '1'} checked{/if} /> {#operation#}
            </label>
        </div>
    </div>
    <div class="col-ph-12 col-md-4">
        {if $row}
        <input type="hidden" id="id_access{$row.id_access}" name="id_access" value="{$row.id_access}" required>
        <input type="hidden" id="id_role{$row.id_access}" name="id" value="{$id}" required>
        <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
        <button class="btn btn-link text-success hide" type="button"><span class="fa fa-check"></span>&nbsp;{#saved#|ucfirst}</button>
        {else}
        <input type="hidden" id="id_role" name="id" value="{$id}" required>
        <button class="btn btn-main-theme" type="submit">{#add#|ucfirst}</button>
        {/if}
    </div>
</div>