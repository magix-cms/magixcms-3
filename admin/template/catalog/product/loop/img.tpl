{if isset($data) && !empty($data)}
    <div class="row sortable">
    {foreach $data as $key => $value}
        <div id="image_{$value.id_img}" class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <input type="checkbox" name="img[{$value.id_img}]" id="{$value.name_img}" value="{$value.id_img}">
            <label for="{$value.name_img}">
                <figure>
                    <div class="center-img">
                        <img class="img-responsive" src="/upload/{if isset($uploadDir)}{$uploadDir}{else}{$smarty.get.controller}{/if}/{$value.id_product}/s_{$value.name_img}" />
                    </div>
                </figure>
            </label>
            <div class="default fade{if $value.default_img} in{/if}">
                <span class="fa fa-check text-success"></span>
                Image par défaut
            </div>
            <div class="make-default{if $value.default_img} hide{/if}">
                <a href="#" class="make_default" data-id="{$value.id_img}" data-edit="{$value.id_product}">
                    <span class="fa fa-circle-thin"></span>
                    Image par défaut
                </a>
            </div>
            <div class="btn-group actions btn-group-justified" role="group">
                <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$value.id_product}&editimg={$value.id_img}" type="button" class="btn btn-default"><i class="material-icons">edit</i></a>
                <a type="button" class="btn btn-default img-zoom" href="/upload/{if isset($uploadDir)}{$uploadDir}{else}{$smarty.get.controller}{/if}/{$value.id_product}/l_{$value.name_img}"><i class="material-icons">zoom_in</i></a>
                <a href="#" type="button" class="btn btn-default action_on_record modal_action" data-id="{$value.id_img}" data-controller="product" data-sub="image" data-target="#delete_modal"><i class="material-icons">delete</i></a>
            </div>
        </div>
    {/foreach}
    </div>
{/if}