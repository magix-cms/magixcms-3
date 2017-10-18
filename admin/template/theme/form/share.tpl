<div class="row">
    <form id="edit_socials" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-sm-6 col-md-4">
        <h3>Partager sur&nbsp;:</h3>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="share-facebook" name="share[facebook]" class="switch-native-control"{if $shareConfig.facebook} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="share-facebook">Facebook</label>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-ph-12 col-xs-6 col-md-4">
                    <div class="switch">
                        <input type="checkbox" id="share-twitter" name="share[twitter]" class="switch-native-control"{if $shareConfig.twitter} checked{/if} />
                        <div class="switch-bg">
                            <div class="switch-knob"></div>
                        </div>
                    </div>
                    <label for="share-twitter">Twitter</label>
                </div>
                <div class="col-ph-12 col-xs-6 col-md-8 form-inline">
                    <label for="twitter_id">Twitter ID</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="fa fa-at"></span></span>
                        <input type="text" id="twitter_id" name="twitter_id" class="form-control" placeholder="Twitter ID" aria-describedby="basic-addon1"{if $twitter_id} value="{$twitter_id}"{/if}{if !$shareConfig.twitter} disabled{/if}>
                    </div>
                    <a href="#" class="text-info" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Requis pour utiliser les Twitter Card | Nécessite une autorisation pour le compte">
                        <span class="fa fa-question-circle"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="share-viadeo" name="share[viadeo]" class="switch-native-control"{if $shareConfig.viadeo} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="share-viadeo">Viadeo</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="share-google-plus" name="share[google]" class="switch-native-control"{if $shareConfig.google} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="share-google-plus">Google+</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="share-linkedin" name="share[linkedin]" class="switch-native-control"{if $shareConfig.linkedin} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="share-linkedin">LinkedIn</label>
        </div>
        <div class="form-group">
            <div class="switch">
                <input type="checkbox" id="share-pinterest" name="share[pinterest]" class="switch-native-control"{if $shareConfig.pinterest} checked{/if} />
                <div class="switch-bg">
                    <div class="switch-knob"></div>
                </div>
            </div>
            <label for="share-pinterest">Pinterest</label>
        </div>
        <input type="hidden" id="data_type" name="data_type" value="share">
        <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>