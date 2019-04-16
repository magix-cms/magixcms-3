<div class="row">
    <form id="delete_img_holder" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=delete" method="post" class="validate_form delete_form_img col-ph-12" data-target="holder">
        <div class="form-group">
            <input type="hidden" id="del_holder" name="del_holder" value="">
            <button class="btn btn-danger" type="submit" name="action" value="img">{#remove#|ucfirst}</button>
        </div>
    </form>
    <form id="edit_img_default" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form_img col-ph-12 col-md-6 col-lg-5" data-target="holder">
        <div class="form-group">
            <label for="color[canvas]">{#canvas_color#} <a href="#" class="icon-help text-info" data-trigger="hover" data-placement="top"
                                                           data-toggle="popover"
                                                           data-content="{#colorpicker_info#}"
                                                           data-original-title=""
                                                           data-title="">
                    <span class="fa fa-question-circle"></span>
                </a>:</label>
            <div class="input-group colorpicker-component csspicker">
                <input type="text" value="#ffffff" class="form-control" name="color[canvas]" />
                <span class="input-group-addon"><i></i></span>
            </div>
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
    <div class="col-ph-12 col-md-6 col-lg-7">
        <h3>{#available_sizes#}</h3>
        <div class="block-img" id="holder">
            {if $holder != null}
                {include file="logo/brick/holder.tpl"}
            {/if}
        </div>
    </div>
</div>