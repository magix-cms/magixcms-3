<div class="row">
    <div class="col-ph-12">
        {include file="section/form/progressBar.tpl"}
    </div>
    <form id="add_img_news" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_news}" method="post" enctype="multipart/form-data" class="form-gen col-ph-12">
        <div class="dropzone multi-img-drop">
            {*Déposez vos images ici...*}
            <div class="drop-buttons form-group">
                <div class="drop-text">{#drop_imgs_here#}</div>
                <label class="btn btn-default" for="imgs">ou cliquez ici.. <span class="fa fa-upload"></span></label>
                <button class="btn btn-main-theme" type="submit" name="action" value="img" disabled>{#send#|ucfirst}</button>
            </div>
            <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
            <input type="hidden" id="news[id]" name="id" value="{$page.id_news}">
            <input type="file" accept="image/*" id="imgs" name="img_multiple[]" value="" multiple />
        </div>
    </form>
</div>