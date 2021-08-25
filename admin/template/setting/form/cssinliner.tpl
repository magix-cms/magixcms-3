<form id="edit_setting_cssinliner" action="{$smarty.server.SCRIPT_NAME}?controller={$controller}&amp;action=edit" method="post" class="validate_form edit_form">
    <fieldset>
        <legend>{#css_inliner#}</legend>
        <div class="row">
            <div class="col-ph-12 col-sm-10 col-md-8 col-lg-6">
                <div class="form-group">
                    <div class="switch">
                        <input type="checkbox" data-target="#cssinliner_color" id="css_inliner" name="setting[css_inliner]" class="switch-native-control optional-fields"{if isset($settings.css_inliner) && $settings.css_inliner eq '1'} checked{/if} />
                        <div class="switch-bg">
                            <div class="switch-knob"></div>
                        </div>
                    </div>
                    <label for="css_inliner">
                        {#css_inliner_enabled#}&nbsp;?
                        <a href="#" class="text-warning" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="{#css_inliner_warning#}">
                            <span class="fa fa-question-circle"></span>
                        </a>
                    </label>
                </div>
                <div id="cssinliner_color" class="collapse">
                    <p class="h5">{#css_inliner_header#}</p>
                    <div class="form-inline">
                        <label for="header_bg">{#css_inliner_bg#}</label>
                        <div class="input-group colorpicker-component csspicker">
                            <input id="header_bg" type="text" value="#f2f2f2" class="form-control" name="color[header_bg]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                        <label for="header_c">{#css_inliner_c#}</label>
                        <div class="input-group colorpicker-component csspicker">
                            <input id="header_c" type="text" value="#ffffff" class="form-control" name="color[header_c]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                    <p class="h5">{#css_inliner_footer#}</p>
                    <div class="form-inline">
                        <label for="footer_bg">{#css_inliner_bg#}</label>
                        <div class="input-group colorpicker-component csspicker">
                            <input id="footer_bg" type="text" value="#f333333" class="form-control" name="color[footer_bg]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                        <label for="footer_c">{#css_inliner_c#}</label>
                        <div class="input-group colorpicker-component csspicker">
                            <input id="footer_c" type="text" value="#ffffff" class="form-control" name="color[footer_c]" />
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>
                </div>
                <div id="submit">
                    <input type="hidden" id="type" name="type" value="css_inliner">
                    <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                </div>
            </div>
        </div>
    </fieldset>
</form>