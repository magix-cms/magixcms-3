<form id="add_news" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
    {include file="language/brick/dropdown-lang.tpl"}
    <div class="tab-content">
        {foreach $langs as $id => $iso}
            <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                <div class="row">
                    <div class="col-ph-12 col-sm-8">
                        <div class="form-group">
                            <label for="content[{$id}][name_news]">{#title#|ucfirst} :</label>
                            <input type="text" class="form-control" id="content[{$id}][name_news]" name="content[{$id}][name_news]" value="" size="50" />
                        </div>
                    </div>
                    <div class="col-ph-12 col-sm-4">
                        <div class="form-group">
                            <label for="content[{$id}][published_news]">Statut</label>
                            <input id="content[{$id}][published_news]" data-toggle="toggle" type="checkbox" name="content[{$id}][published_news]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-ph-12 col-sm-4">
                        <div class="form-group">
                            <label for="content[{$id}][date_publish]">Date de publication</label>
                            <div class="input-group date date-input-picker">
                                <input type="text" class="form-control" id="content[{$id}][date_publish]" name="content[{$id}][date_publish]" value="" size="50" />
                                <span class="input-group-addon">
                                        <span class="far fa-calendar-plus"></span>
                                    </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="content[{$id}][resume_news]">{#resume#|ucfirst} :</label>
                    <textarea name="content[{$id}][resume_news]" id="content[{$id}][resume_news]" class="form-control">{$page.content[{$id}].resume_news}</textarea>
                </div>
                <div class="form-group">
                    <label for="content[{$id}][content_news]">{#content#|ucfirst} :</label>
                    <textarea name="content[{$id}][content_news]" id="content[{$id}][content_news]" class="form-control mceEditor"></textarea>
                </div>
            </fieldset>
        {/foreach}
    </div>
    <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
</form>