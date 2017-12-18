<form id="add_imagesize" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
    <div class="row">
        <div class="col-ph-12 col-md-12">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="module_img">{#module#|ucfirst}</label>
                        <select name="module_img" id="module_img" class="form-control required" required>
                            <option value="">{#ph_module#|ucfirst}</option>
                            {foreach $module as $key => $val}
                                {$newVal = "module_"|cat:$val}
                                <option value="{$val}">{#$newVal#|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="attribute_img">{#attribute#|ucfirst}</label>
                        <input type="text" class="form-control required" name="attribute_img" id="attribute_img" placeholder="{#ph_attribute#|ucfirst}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="width_img">{#width#|ucfirst}</label>
                        <input type="number" class="form-control required" name="width_img" id="width_img" placeholder="{#ph_width#|ucfirst}" required>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="height_img">{#height#|ucfirst}</label>
                        <input type="number" class="form-control required" name="height_img" id="height_img" placeholder="{#ph_height#|ucfirst}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="type_img">{#type#|ucfirst}</label>
                        <select name="type_img" id="type_img" class="form-control required" required>
                            <option value="">{#ph_type#|ucfirst}</option>
                            {foreach $type as $key => $val}
                                <option value="{$val}">{#$val#|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="resize_img">{#resize#|ucfirst}</label>
                        <select name="resize_img" id="resize_img" class="form-control required" required>
                            <option value="">{#ph_resize#|ucfirst}</option>
                            {foreach $resize as $key => $val}
                                <option value="{$val}">{$val|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-ph-12 col-md-12">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
        </div>
    </div>
</form>