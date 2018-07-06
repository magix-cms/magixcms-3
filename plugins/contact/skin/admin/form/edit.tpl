<form id="add_contact" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$contact.id_contact}" method="post" class="validate_form edit_form col-ph-12">
    {include file="language/brick/dropdown-lang.tpl"}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="email_contact">{#email_contact#|ucfirst}&nbsp;</label>
                <input type="text" name="email_contact" id="email_contact" class="form-control" placeholder="{#email_contact#}" value="{$contact.email_contact}" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10">
            <div class="tab-content">
                {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>{#active#|ucfirst}&nbsp;*</label>
                                    <div class="radio">
                                        <label for="content[{$id}][active_contact]_1">
                                            <input type="radio" name="content[{$id}][active_contact]" id="content[{$id}][active_contact]_1" value="1" {if $contact.content[{$id}].active_contact == 1} checked{/if}>
                                            {#bin_1#}
                                        </label>
                                        <label for="content[{$id}][active_contact]_0">
                                            <input type="radio" name="content[{$id}][active_contact]" id="content[{$id}][active_contact]_0" value="0" {if $contact.content[{$id}].active_contact == 0} checked{/if}>
                                            {#bin_0#}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                {/foreach}
            </div>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-6">
            <input type="hidden" id="id_contact" name="id" value="{$contact.id_contact}">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </div>
</form>