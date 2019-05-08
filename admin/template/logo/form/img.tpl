<div class="row">
    <form id="delete_img_logo" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=delete" method="post" class="validate_form delete_form_img col-ph-12" data-target="logo_img">
        <div class="form-group">
            <input type="hidden" id="del_img" name="del_img" value="">
            <button class="btn btn-danger" type="submit" name="action" value="img">{#remove#|ucfirst}</button>
        </div>
    </form>
    {foreach $langs as $id => $iso}
        {if $iso@first}{$default = $id}{break}{/if}
    {/foreach}
    <form id="edit_img_logo" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form_img col-ph-12 col-md-6 col-lg-5" data-target="logo_img">
        <h3>Image, description de l'image et SEO <a href="#" class="icon-help text-info" data-trigger="hover" data-placement="top"
                                                    data-toggle="popover"
                                                    data-content="{#info_size_logo#}"
                                                    data-original-title=""
                                                    data-title="">
                <span class="fa fa-question-circle"></span>
            </a></h3>
        <div class="form-group">
            <label for="name_img_{$id}">{#name_img#|ucfirst} :</label>
            <input type="text" class="form-control" placeholder="{#ph_name_img#}" id="name_img_{$id}" name="name_img" value="{if isset($page.img_logo)}{$page.img_logo}{else}{$page.content[$default].url_pages}{/if}" />
        </div>
        <div class="form-group">
            <label class="radio-inline">
                <input type="radio" name="active_logo" value="on" {if $page.active_logo eq '1'} checked{/if} {if !$page.active_logo eq '1' && !$page.active_logo eq '0'}checked{/if}>
                {#active_logo#|ucfirst}
            </label>
            <label class="radio-inline">
                    <input type="radio" name="active_logo" value="off" {if $page.active_logo eq '0'} checked{/if}>
                    {#unactive_logo#|ucfirst}
            </label>
        </div>
        <div id="drop-zone" class="img-drop{if !isset($page.imgSrc) || empty($page.imgSrc)} no-img{/if}">
            <div id="drop-buttons" class="form-group">
                <label id="clickHere" class="btn btn-default">
                    ou cliquez ici.. <span class="fa fa-upload"></span>
                    <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
                    <input type="file" id="img" name="img" />
                </label>
            </div>
            <div class="preview-img">
                <img id="preview"
                     src="{if isset($page.imgSrc) && !empty($page.imgSrc)}/img/logo/{$page.imgSrc['original'].img}{else}#{/if}"
                     alt="Déposez votre images ici..."
                     class="{if isset($page.imgSrc) && !empty($page.imgSrc)}preview{else}no-img{/if} img-responsive" />
            </div>
        </div>
        {include file="language/brick/dropdown-lang.tpl" onclass="true"}
        <div class="tab-content">
            {foreach $langs as $id => $iso}
                <div role="tabpanel" class="tab-pane{if $iso@first} active{/if} lang-{$id}">
                    <fieldset>
                        <legend>Texte</legend>
                        <div class="row">
                            <div class="col-ph-12 col-md-6">
                                <div class="form-group">
                                    <label for="alt_img_{$id}">{#alt_img#|ucfirst} :</label>
                                    <input type="text" class="form-control" id="alt_img_{$id}" name="content[{$id}][alt_logo]" placeholder="{#ph_alt_img#}" value="{$page.content[{$id}].alt_logo}" />
                                </div>
                            </div>
                            <div class="col-ph-12 col-md-6">
                                <div class="form-group">
                                    <label for="title_img_{$id}">{#title_img#|ucfirst} :</label>
                                    <input type="text" class="form-control" id="title_img_{$id}" name="content[{$id}][title_logo]" placeholder="{#ph_title_img#}" value="{$page.content[{$id}].title_logo}" />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            {/foreach}
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            <button class="btn btn-main-theme" type="submit" {*name="action" value="edit"*}>{#save#|ucfirst}</button>
        </fieldset>
    </form>
    <div class="col-ph-12 col-md-6 col-lg-7">
        <h3>Tailles disponibles</h3>
        <div class="block-img" id="logo_img">
            {if $page.imgSrc != null}
                {include file="logo/brick/img.tpl"}
            {/if}
        </div>
    </div>
</div>