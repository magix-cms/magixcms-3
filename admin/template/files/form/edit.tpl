{include file="section/form/progressBar.tpl"}
<form id="edit_imagesize" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&amp;edit={$size.id_config_img}" method="post" class="form-regen">
    <div class="row">
        <div class="col-ph-12 col-md-12">
            <div class="row">
                {*<div class="col-xs-6">
                    <div class="form-group">
                        <label for="module_img">{#module#|ucfirst}</label>
                        <select name="imageConfig[module_img]" id="module_img" class="form-control required" required>
                            <option value="">{#ph_module#|ucfirst}</option>
                            {foreach $module as $key => $val}
                                {$newVal = "module_"|cat:$val}
                                <option value="{$val}" {if $size.module_img == $val} selected{/if}>{#$newVal#|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>*}
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="module_img">{#module#|ucfirst}</label>
                        <input type="text" class="form-control required" name="imageConfig[module_img]" id="module_img" placeholder="{#ph_module#|ucfirst}" required value="{$size.module_img}">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="attribute_img">{#attribute#|ucfirst}</label>
                        <input type="text" class="form-control required" name="imageConfig[attribute_img]" id="attribute_img" placeholder="{#ph_attribute#|ucfirst}" required value="{$size.attribute_img}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    {*<div class="form-group">
                        <label for="type_img">{#type#|ucfirst}</label>
                        <select name="type_img" id="type_img" class="form-control required" required>
                            <option value="">{#ph_type#|ucfirst}</option>
                            {foreach $type as $key => $val}
                                <option value="{$val}" {if $size.type_img == $val} selected{/if}>{#$val#|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>*}
                    <div class="form-group">
                        <label for="type_img">{#type#|ucfirst}</label>
                        <input type="text" class="form-control required" name="imageConfig[type_img]" id="type_img" placeholder="{#ph_type#|ucfirst}" value="{$size.type_img}" required>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="prefix_img">{#prefix#|ucfirst}</label>
                        <input type="text" class="form-control required" name="imageConfig[prefix_img]" id="prefix_img" placeholder="{#ph_prefix#|ucfirst}" value="{$size.prefix_img}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="width_img">{#width#|ucfirst}</label>
                        <input type="number" class="form-control required" name="imageConfig[width_img]" id="width_img" placeholder="{#ph_width#|ucfirst}" required value="{$size.width_img}">
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="height_img">{#height#|ucfirst}</label>
                        <input type="number" class="form-control required" name="imageConfig[height_img]" id="height_img" placeholder="{#ph_height#|ucfirst}" required value="{$size.height_img}">
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label for="resize_img">{#resize#|ucfirst}</label>
                        <select name="imageConfig[resize_img]" id="resize_img" class="form-control required" required>
                            <option value="">{#ph_resize#|ucfirst}</option>
                            {foreach $resize as $key => $val}
                                <option value="{$val}" {if $size.resize_img == $val} selected{/if}>{$val|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-ph-12 col-md-12">
            <input type="hidden" id="id_config_img" name="imageConfig[id_config_img]" value="{$size.id_config_img}">
            <button class="btn btn-main-theme pull-right" type="submit">{#save_and_apply#|ucfirst}</button>
        </div>
    </div>
</form>