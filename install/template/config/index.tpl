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
        <form id="config-form" class="validate_form" method="post" action="{$smarty.server.REQUEST_URI}">
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_DBHOST">Host*&nbsp;:</label>
                        <input id="M_DBHOST" type="text" name="M_DBHOST" placeholder="Host" class="form-control required" value="localhost" required/>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_DBDRIVER">Driver*&nbsp;:</label>
                        <select id="M_DBDRIVER" name="M_DBDRIVER" class="form-control required">
                            <option value="mysql">Mysql</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_DBUSER">User*&nbsp;:</label>
                        <input id="M_DBUSER" type="text" name="M_DBUSER" placeholder="User" class="form-control required" value="" required/>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_DBPASSWORD">password*&nbsp;:</label>
                        <input id="M_DBPASSWORD" type="password" name="M_DBPASSWORD" placeholder="password" class="form-control required" value="localhost" required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_DBNAME">DBName*&nbsp;:</label>
                        <input id="M_DBNAME" type="text" name="M_DBNAME" placeholder="DBName" class="form-control required" value="" required/>
                    </div>
                </div>
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_LOG">Log*&nbsp;:</label>
                        <select id="M_LOG" name="M_LOG" class="form-control required">
                            <option value="log">LOG</option>
                            <option value="debug">DEBUG</option>
                            <option value="false" selected="selected">OFF</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-ph-12 col-sm-6">
                    <div class="form-group">
                        <label for="M_FIREPHP">FirePHP*&nbsp;:</label>
                        <select id="M_FIREPHP" name="M_FIREPHP" class="form-control required">
                            <option value="true">ON</option>
                            <option value="false" selected="selected">OFF</option>
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