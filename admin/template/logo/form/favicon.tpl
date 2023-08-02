<div class="row">
    <form id="delete_img_favicon" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=delete&tabs=favicon" method="post" class="validate_form delete_form_img col-ph-12" data-target="favicon_img">
        <div class="form-group">
            <input type="hidden" id="del_favicon" name="del_favicon" value="del_favicon">
            <button class="btn btn-danger" type="submit">{#remove#|ucfirst}</button>
        </div>
    </form>
    <form id="edit_img_favicon" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&tabs=favicon" method="post" class="validate_form edit_form_img col-ph-12 col-md-6 col-lg-5" data-target="favicon_img">
        <h3>Image</h3>
        {*<div class="dropzone img-drop{if !isset($favicon) || empty($favicon)} no-img{/if}">
            <div class="form-group drop-buttons">
                <label class="btn btn-default">
                    ou cliquez ici.. <span class="fa fa-upload"></span>
                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
                    <input type="file" accept="image/*" class="input-img" name="fav" />
                </label>
            </div>
            <div class="preview-img">
                <img
                     src="{if isset($favicon) && !empty($favicon)}/img/favicon/fav.png{else}#{/if}"
                     alt="Déposez votre images ici..."
                     class="{if isset($favicon) && !empty($favicon)}preview{else}no-img{/if} img-responsive" />
            </div>
        </div>*}
        <div class="dropzone img-drop{if !isset($favicon) || empty($favicon)} no-img{/if}" data-preview="true">
            <div class="preview-img">
                <img
                        src="{if isset($favicon) && !empty($favicon)}/img/favicon/fav.png{else}#{/if}"
                        alt="{#drop_img_here#}"
                        class="{if isset($favicon) && !empty($favicon)}preview{else}no-img{/if} img-responsive" />
            </div>
            <div class="drop-buttons form-group">
                {*<div class="drop-text">{#drop_here#}</div>*}
                <label class="btn btn-default" for="fav">ou cliquez ici.. <span class="fa fa-upload"></span></label>
            </div>
            <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />
            <input type="file" accept="image/*" id="fav" name="fav" />
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            <button class="btn btn-main-theme" type="submit" {*name="action" value="edit"*}>{#save#|ucfirst}</button>
        </fieldset>
    </form>
    <div class="col-ph-12 col-md-6 col-lg-7">
        <h3>Tailles disponibles</h3>
        <div class="block-img" id="favicon_img">
            {if $favicon != null}
                {include file="logo/brick/favicon.tpl"}
            {/if}
        </div>
    </div>
</div>