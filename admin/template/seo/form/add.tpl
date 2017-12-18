{assign var="collectionformLevel" value=[
"root",
"parent",
"record"
]}
{assign var="collectionformType" value=[
"title",
"description"
]}
<form id="add_seo" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
    {include file="language/brick/dropdown-lang.tpl"}
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <label for="attribute_seo">{#attribute#|ucfirst}</label>
                <select name="attribute_seo" id="attribute_seo" class="form-control required" required>
                    <option value="">{#ph_attribute#|ucfirst}</option>
                    {foreach $collectionModule as $key => $val}
                        <option value="{$val}">{$val|ucfirst}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="level_seo">{#level#|ucfirst}</label>
                <select name="level_seo" id="level_seo" class="form-control required" required>
                    <option value="">{#ph_level#|ucfirst}</option>
                    {foreach $collectionformLevel as $key => $val}
                        {$newVal = $val|cat:"_level"}
                        <option value="{$val}">{#$newVal#|ucfirst}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <label for="type_seo">{#type#|ucfirst}</label>
                <select name="type_seo" id="type_seo" class="form-control required" required>
                    <option value="">{#ph_type#|ucfirst}</option>
                    {foreach $collectionformType as $key => $val}
                        {$newVal = "type_"|cat:$val}
                        <option value="{$val}">{#$newVal#|ucfirst}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div class="tab-content">
        {foreach $langs as $id => $iso}
            <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                <div class="row">
                    <div class="col-ph-12 col-sm-12">
                        <div class="form-group">
                            <label for="content[{$id}][content_seo]">{#content#|ucfirst} :</label>
                            <textarea name="content[{$id}][content_seo]" id="content-{$id}-content_seo" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="keywords">
                    <ul class="list-inline">
                        <li>
                            <a href="#" class="keyword btn btn-default" data-keyword="[[PARENT]]" data-target="content-{$id}-content_seo">
                                <span class="fa fa-plus-square"></span> Parent
                            </a>
                        </li>
                        <li>
                            <a href="#" class="keyword btn btn-default" data-keyword="[[RECORD]]" data-target="content-{$id}-content_seo">
                                <span class="fa fa-plus-square"></span> Record
                            </a>
                        </li>
                    </ul>
                </div>
            </fieldset>
        {/foreach}
    </div>
    <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
</form>