{assign var="availability" value=[
'InStock'=>'instock','OnlineOnly'=>'onlineonly','BackOrder'=>'backorder','SoldOut'=>'soldout'
]}
<form id="edit_product_properties" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_product}&tab=properties" method="post" class="validate_form edit_form_extend">
    {*<h4>{#properties#}</h4>*}
    <div class="row">
        <div class="col-ph-12 col-sm-8">
            <div class="row">
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="width_p">{#width#}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="width_p" name="productData[width]" value="{$page.width_p}" placeholder="{#ph_width#|ucfirst}">
                            <span class="input-group-addon">
                                cm
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="height_p">{#height#}</label>
                        <div class="input-group">
                        <input type="text" class="form-control" id="height_p" name="productData[height]" value="{$page.height_p}" placeholder="{#ph_height#|ucfirst}" />
                            <span class="input-group-addon">
                                cm
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="depth_p">{#depth#}</label>
                        <div class="input-group">
                        <input type="text" class="form-control" id="depth_p" name="productData[depth]" value="{$page.depth_p}" placeholder="{#ph_depth#|ucfirst}">
                            <span class="input-group-addon">
                                cm
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-ph-12 col-md-4">
                    <div class="form-group">
                        <label for="weight_p">{#weight#}</label>
                        <div class="input-group">
                        <input type="text" class="form-control" id="weight_p" name="productData[weight]" value="{$page.weight_p}" placeholder="{#ph_weight#|ucfirst}" />
                            <span class="input-group-addon">
                                kg
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-md-8">
                    <div class="form-group">
                        <label for="availability_p">{#availability#|ucfirst}</label>
                        <select name="productData[availability]" id="availability_p" class="form-control">
                            <option value="">{#ph_availability#|ucfirst}</option>
                            {foreach $availability as $key => $value}
                                <option value="{$key}" {if $page.availability_p == $key} selected{/if}>{#$value#|ucfirst}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id_properties" name="id" value="{$page.id_product}">
    <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
</form>