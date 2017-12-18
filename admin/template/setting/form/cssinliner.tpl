<form id="edit_setting_cssinliner" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <div class="row">
        <div class="col-ph-12 col-md-4">
            <header>
                <div class="form-group">
                    <label for="cssinliner_enabled">{#cssinliner#|ucfirst}&nbsp;?</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="optional-fields" id="cssinliner_enabled" name="setting[css_inliner]" data-target="#cssinliner_color" data-toggle="toggle" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if isset($settings.css_inliner) && $settings.css_inliner eq '1'} checked{/if}/>
                        </label>
                    </div>
                </div>
            </header>
            <div id="cssinliner_color" class="collapse">
                <h3>
                    Header
                </h3>
                <div class="row">
                    <div class="form-group col-ph-12 col-md-6">
                        <label>
                            Background
                        </label>
                        <div class="input-group colorpicker-component csspicker">
                            <input type="text" value="#f2f2f2" class="form-control" name="color[header_bg]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <div class="form-group col-ph-12 col-md-6">
                        <label>
                            Color
                        </label>
                        <div class="input-group colorpicker-component csspicker">
                            <input type="text" value="#ffffff" class="form-control" name="color[header_c]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>
                <h3>
                    Footer
                </h3>
                <div class="row">
                    <div class="form-group col-ph-12 col-md-6">
                        <label>
                            Background
                        </label>
                        <div class="input-group colorpicker-component csspicker">
                            <input type="text" value="#333333" class="form-control" name="color[footer_bg]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <div class="form-group col-ph-12 col-md-6">
                        <label>
                            Color
                        </label>
                        <div class="input-group colorpicker-component csspicker">
                            <input type="text" value="#ffffff" class="form-control" name="color[footer_c]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="submit">
                <input type="hidden" id="type" name="type" value="css_inliner">
                <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
            </div>
        </div>
    </div>
</form>