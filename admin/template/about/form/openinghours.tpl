<div class="row">
    <form id="enable_op_form" method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-10">
        <div class="row">
            <div class="col-ph-12 col-md-4">
                <div class="form-group">
                    <label for="enable_op">{#op_enabled#|ucfirst}</label>
                    <input id="enable_op" data-toggle="toggle" type="checkbox" name="enable_op" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $companyData.openinghours} checked{/if}>
                </div>
            </div>
        </div>
        <input type="hidden" id="data_type" name="data_type" value="enable_op">
    </form>
    <form id="info_opening_form" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-10 {if $companyData.openinghours} collapse{else} collapse in{/if}">
        {include file="language/brick/dropdown-lang.tpl" onclass=true}
        <table id="openingHours" class="table table-bordered">
            <thead>
            <tr>
                <th>{#op_day#|ucfirst}</th>
                <th class="text-center">{#op_open#|ucfirst}</th>
                <th class="text-center">{#op_open#|ucfirst}&nbsp;{#op_from#}</th>
                <th class="text-center">{#op_to#}</th>
                <th class="text-center">
                    <span class="fa fa-coffee"></span>
                    <a href="#" class="text-info" data-trigger="hover" data-container="body" data-toggle="popover" data-placement="top" data-content="{#op_noon_ph#|ucfirst}">
                        <span class="fa fa-question-circle"></span>
                    </a>
                </th>
                <th class="text-center">{#op_from#}</th>
                <th class="text-center">{#op_to#}</th>
            </tr>
            </thead>
            <tbody>
            {include file="about/loop/days.tpl"}
            </tbody>
        </table>
        <div id="submit">
            <input type="hidden" id="data_type" name="data_type" value="openinghours">
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </form>
</div>