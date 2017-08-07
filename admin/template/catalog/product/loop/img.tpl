{if isset($data) && !empty($data)}
    <div class="row sortable">
    {foreach $data as $key => $value}
        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <input type="checkbox" name="img[{$value.id_img}]" id="img[{$value.id_img}]">
            <label for="img[{$value.id_img}]">
                <figure>
                    <div class="center-img">
                        <img class="img-responsive" src="/upload/{if isset($uploadDir)}{$uploadDir}{else}{$smarty.get.controller}{/if}/{$value.id_product}/s_{$value.name_img}" />
                    </div>
                </figure>
            </label>
            {if $value.default_img}
                <div class="default">
                    <span class="fa fa-check text-success"></span>
                    Image par défaut
                </div>
            {else}
                <div class="make-default">
                    <a href="#" class="make_default">
                        <span class="fa fa-circle-thin"></span>
                        Image par défaut
                    </a>
                </div>
            {/if}
            <div class="btn-group actions btn-group-justified" role="group">
                <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$value.id_product}&editimg={$value.id_img}" type="button" class="btn btn-default"><span class="fa fa-pencil-square-o"></span></a>
                <a type="button" class="btn btn-default img-zoom" href="/upload/{if isset($uploadDir)}{$uploadDir}{else}{$smarty.get.controller}{/if}/{$value.id_product}/l_{$value.name_img}"><span class="fa fa-search-plus"></span></a>
                <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=delete&editimg={$value.id_img}" type="button" class="btn btn-default action_on_record modal_action"><span class="fa fa-trash"></span></a>
            </div>
        </div>
    {/foreach}
    </div>
{/if}