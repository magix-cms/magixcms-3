{capture name="privateLink"}<a href="{#private_data_url#}" class="targetblank" title="{#private_data_title#}">{#private_data_label#}</a>{/capture}
{capture name="cookieLink"}<a href="{#cookie_page_url#}" class="targetblank" title="{#cookie_page_title#}">{#cookie_read_label#|ucfirst}</a>{/capture}
<div id="rgpd-compliance" class="fade in hide">
    <p>{sprintf({#cookie_text#},$smarty.capture.cookieLink,$smarty.capture.privateLink)}</p>
    <div class="btns">
        <button class="btn btn-link refuseRgpd" type="button">{#refuse_cookies#}</button>
        <button class="btn btn-default" type="button" id="paramCookies" data-toggle="modal" data-target="#cookiesModal">{#param_cookies#}</button>
        <button class="btn btn-main-outline acceptRgpd" type="button">{#accept_cookies#}</button>
    </div>
</div>
<div class="modal" id="cookiesModal" role="dialog" aria-labelledby="cookieModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h1 text-center" id="cookieModalTitle">{#cookieModalTitle#}</p>
            </div>
            <form action="#" class="modal-body validate_form refresh_form">
                <p>{sprintf({#cookie_modal#},$smarty.capture.cookieLink,$smarty.capture.privateLink)}</p>
                <div class="vertical-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        {*<li role="presentation">
                            <a role="tab" href="#essential_cookies" aria-controls="essential_cookies" data-toggle="tab">{#essential_cookies#}</a>
                        </li>*}
                        <li role="presentation" class="active">
                            <a role="tab" href="#analytic_cookies" aria-controls="analytic_cookies" data-toggle="tab">{#analytic_cookies#}</a>
                        </li>
                        <li role="presentation">
                            <a role="tab" href="#google_cookies" aria-controls="other_cookies" data-toggle="tab">{#google_cookies#}</a>
                        </li>
                        {*<li role="presentation">
                            <a role="tab" href="#other_cookies" aria-controls="other_cookies" data-toggle="tab">{#other_cookies#}</a>
                        </li>*}
                    </ul>
                    <div id="colors" class="tab-content">
                        {*<div id="essential_cookies" class="tab-pane" role="tabpanel">
                            <p>{#essential_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="essentialCookies" name="essentialCookies" class="switch-native-control" checked disabled/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="essentialCookies">{#accept_essential_cookies#}</label>
                            </div>
                        </div>*}
                        <div id="analytic_cookies" class="tab-pane active" role="tabpanel">
                            <p>{#analytic_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="analyticCookies" name="analyticCookies" class="switch-native-control"{if $consentedCookies.analyticCookies} checked{/if}/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="analyticCookies">{#accept_analytic_cookies#}</label>
                            </div>
                        </div>
                        <div id="google_cookies" class="tab-pane" role="tabpanel">
                            <p>{#google_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="ggWebfontCookies" name="ggWebfontCookies" class="switch-native-control"{if $consentedCookies.ggWebfontCookies} checked{/if}/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="ggWebfontCookies">{#accept_ggwebfontcookies#}</label>
                            </div>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="embedCookies" name="embedCookies" class="switch-native-control"{if $consentedCookies.embedCookies} checked{/if}/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="embedCookies">{#accept_embedcookies#}</label>
                            </div>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="ggMapCookies" name="ggMapCookies" class="switch-native-control"{if $consentedCookies.ggMapCookies} checked{/if}/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="ggMapCookies">{#accept_ggmapcookies#}</label>
                            </div>
                        </div>
                        {*<div id="other_cookies" class="tab-pane" role="tabpanel">
                            <p>{#other_cookies#}</p>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="adobeWebfontCookies" name="adobeWebfontCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="adobeWebfontCookies">Accepter/Refuser les adobeWebfontCookies</label>
                            </div>
                            <div class="form-group">
                                <div class="switch">
                                    <input type="checkbox" id="timeZoneOffsetCookies" name="timeZoneOffsetCookies" class="switch-native-control" checked/>
                                    <div class="switch-bg">
                                        <div class="switch-knob"></div>
                                    </div>
                                </div>
                                <label for="timeZoneOffsetCookies">Accepter/Refuser les timeZoneOffsetCookies</label>
                            </div>
                        </div>*}
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-link refuseRgpd" type="button">{#refuse_cookies#}</button>
                    <button class="btn btn-default saveRgpd" type="button">{#save_cookies#}</button>
                    <button class="btn btn-main acceptRgpd" type="button">{#accept_cookies#}</button>
                </div>
            </form>
        </div>
    </div>
</div>