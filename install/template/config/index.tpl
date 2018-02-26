{extends file="layout.tpl"}
{block name='article:header'}
    <h1>Configuration</h1>
{/block}
{block name='article:content'}
    <div class="mc-message-container clearfix">
        <div class="mc-message"></div>
    </div>
    <div class="row">
    <section id="form" class="col-ph-12 col-md-8 col-lg-7" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
        <form id="config-form" class="validate_form config_form" method="post" action="{$smarty.server.REQUEST_URI}">
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="MP_DBHOST">Host*&nbsp;:</label>
                        <input id="MP_DBHOST" type="text" name="MP_DBHOST" placeholder="Host" class="form-control required" value="localhost" required/>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="MP_DBDRIVER">Driver*&nbsp;:</label>
                        <select id="MP_DBDRIVER" name="MP_DBDRIVER" class="form-control required">
                            <option value="mysql">Mysql</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="MP_DBUSER">User*&nbsp;:</label>
                        <input id="MP_DBUSER" type="text" name="MP_DBUSER" placeholder="User" class="form-control required" value="" required/>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="MP_DBPASSWORD">password*&nbsp;:</label>
                        <input id="MP_DBPASSWORD" type="password" name="MP_DBPASSWORD" placeholder="password" class="form-control required" value="" required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="MP_DBNAME">DBName*&nbsp;:</label>
                        <input id="MP_DBNAME" type="text" name="MP_DBNAME" placeholder="DBName" class="form-control required" value="" required/>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="MP_LOG">Log*&nbsp;:</label>
                        <select id="MP_LOG" name="MP_LOG" class="form-control required">
                            <option value="log" selected="selected">LOG</option>
                            <option value="debug">DEBUG</option>
                            <option value="false">OFF</option>
                        </select>
                    </div>
                </div>
            </div>
            <p id="btn-contact">
                <button type="submit" class="btn btn-box btn-invert btn-main-theme">{#save#|ucfirst}</button>
                {*<button type="button" id="test_connexion" class="btn btn-box btn-invert btn-fr-theme">{#connexion_test#|ucfirst}</button>*}
            </p>
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
            var controller = "/install/employee.php";
            globalForm.run(controller);
        }
    });
</script>
{/block}