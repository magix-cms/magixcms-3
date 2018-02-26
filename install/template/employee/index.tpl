{extends file="layout.tpl"}
{block name='article:header'}
    <h1>Employee</h1>
{/block}
{block name='article:content'}
    <div class="mc-message-container clearfix">
        <div class="mc-message"></div>
    </div>
    <div class="row">
        <section id="form" class="col-ph-12 col-md-8 col-lg-7" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
            <form id="employee-form" class="validate_form add_form" method="post" action="{$smarty.server.REQUEST_URI}">
                <div class="row">
                    <div class="col-ph-12 col-md-6">
                        <div class="form-group radio-group">
                            <label>{#title#|ucfirst}&nbsp;*</label>
                            <div class="radio">
                                <label for="m_admin">
                                    <input type="radio" name="title_admin" id="m_admin" value="m" required>
                                    {#title_m#}
                                </label>
                                <label for="w_admin">
                                    <input type="radio" name="title_admin" id="w_admin" value="w" required>
                                    {#title_w#}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-ph-12 col-md-6">
                                <div class="form-group">
                                    <label for="firstname_admin">{#firstname#|ucfirst}&nbsp;*</label>
                                    <input type="text" class="form-control required" name="firstname_admin" id="firstname_admin" placeholder="{#ph_firstname#|ucfirst}" required>
                                </div>
                            </div>
                            <div class="col-ph-12 col-md-6">
                                <div class="form-group">
                                    <label for="lastname_admin">{#lastname#|ucfirst}&nbsp;*</label>
                                    <input type="text" class="form-control required" name="lastname_admin" id="lastname_admin" placeholder="{#ph_lastname#|ucfirst}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email_admin">{#email#|ucfirst}&nbsp;*</label>
                            <input type="email" class="form-control required" name="email_admin" id="email_admin" placeholder="{#ph_email#|ucfirst}" required>
                        </div>
                    </div>
                    <div class="col-ph-12 col-md-6">
                        <div class="form-group">
                            <label for="passwd_admin">{#passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="passwd_admin" id="passwd_admin" placeholder=" {#ph_passwd#|ucfirst}" required>
                        </div>
                        <div class="form-group">
                            <label for="repeat_passwd">{#repeat_passwd#|ucfirst}&nbsp;*</label>
                            <input type="password" class="form-control required" name="repeat_passwd" id="repeat_passwd" placeholder=" {#repeat_passwd#|ucfirst}" equalTo="#passwd_admin" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="submit" class="col-ph-12 col-md-6">
                        <button class="btn btn-box btn-invert btn-main-theme" type="submit" name="action" value="add">{#save#|ucfirst}</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
{/block}
{block name="foot" append}
    {script src="/install/min/?f=libjs/vendor/localization/messages_fr.js" concat=$concat type="javascript"}
    <script type="text/javascript">
        $(function(){
            if (typeof globalForm == "undefined")
            {
                console.log("globalForm is not defined");
            }else{
                var controller = "{geturl}";
                globalForm.run(controller);
            }
        });
    </script>
{/block}