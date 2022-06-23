<div id="cookies" class="fade in hide" role="alert">
{*    <button type="button" class="btn btn-box btn-invert-white pull-right">{#close_cookie#|ucfirst}</button>*}
    <p>
        <i class="material-icons ico ico-info_outline"></i>{#cookie_text#}
    </p>
    <button class="btn btn-invert-white" type="button" id="paramCookies" data-toggle="modal" data-target="#cookiesModal">
        <span class="sr-ph sr-xs">{#param_cookies#|ucfirst}</span>
    </button>
    <button class="btn btn-invert-white" type="button" id="acceptCookies">
        <span class="sr-ph sr-xs">{#accept_cookies#|ucfirst}</span>
    </button>
</div>
<div class="modal" id="cookiesModal" role="dialog" aria-labelledby="cookieModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h1 text-center" id="cookieModalTitle">{#cookieModalTitle#}</p>
            </div>
            <div class="modal-body">
                {capture name="privateLink"}<a href="{#private_data_url#}" class="targetblank" title="{#private_data_title#}">{#private_data_label#}</a>{/capture}
                {capture name="cookieLink"}<a href="{#cookie_page#}" class="targetblank">{#cookie_read_page#|ucfirst}</a>{/capture}
                <p>{#cookie_modal#|sprintf:$smarty.capture.cookieLink:$smarty.capture.privateLink}</p>
                <form action="#" class="validate_form refresh_form">
                <div class="vertical-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a role="tab" href="#essential_cookies" aria-controls="essential_cookies" data-toggle="tab">{#essential_cookies#}</a>
                        </li>
                        <li role="presentation">
                            <a role="tab" href="#analytic_cookies" aria-controls="analytic_cookies" data-toggle="tab">{#analytic_cookies#}</a>
                        </li>
                        <li role="presentation">
                            <a role="tab" href="#other_cookies" aria-controls="other_cookies" data-toggle="tab">{#other_cookies#}</a>
                        </li>
                    </ul>
                    <div id="colors" class="tab-content">
                        <div id="essential_cookies" class="tab-pane active" role="tabpanel">
                            <p>{#essential_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="essentialCookies" name="essentialCookies" class="switch-native-control" checked disabled/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="essentialCookies">Accepter/Refuser les essentialCookies</label>
                            </div>
                        </div>
                        <div id="analytic_cookies" class="tab-pane" role="tabpanel">
                            <p>{#analytic_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="analyticCookies" name="analyticCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="analyticCookies">Accepter/Refuser les analyticCookies</label>
                            </div>
                        </div>
                        <div id="other_cookies" class="tab-pane" role="tabpanel">
                            <p>{#other_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="ggWebfontCookies" name="ggWebfontCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="ggWebfontCookies">Accepter/Refuser les ggWebfontCookies</label>
                            </div>
                            {*<div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="adobeWebfontCookies" name="adobeWebfontCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="adobeWebfontCookies">Accepter/Refuser les adobeWebfontCookies</label>
                            </div>*}
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="ggMapCookies" name="ggMapCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="ggMapCookies">Accepter/Refuser les ggMapCookies</label>
                            </div>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="embedCookies" name="embedCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="embedCookies">Accepter/Refuser les embedCookies</label>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <div class="modal-footer text-center">
                    <button class="btn btn-main" id="saveCookieParam" type="button">{#save_cookies#}</button>
                </div>
            </div>
        </div>
    </div>
</div>