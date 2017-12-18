{extends file="layout.tpl"}
{block name='head:title'}Web service{/block}
{block name='body:id'}webservice{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="">Gestion du Web Service</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">Création de votre clé API</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                <div class="row">
                    <form id="edit_webservice" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-ph-12 col-md-4">
                        <div class="form-group">
                            <label for="key_ws">Clé API</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="key" id="key_ws" name="key_ws" value="{$ws.key_ws}" size="50">
                                <span class="input-group-btn">
                            <button class="btn btn-success" id="key_generator" type="button">Key generator</button>
                        </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_ws">Status</label>
                            <input id="status_ws" data-toggle="toggle" type="checkbox" name="status_ws" data-toggle="toggle" type="checkbox" data-on="oui" data-off="non" data-onstyle="primary" data-offstyle="default"{if $ws.status_ws} checked{/if}>
                        </div>
                        <div id="submit">
                            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    {/if}
{/block}
{block name="foot" append}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/webservice.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof webservice == "undefined")
            {
                console.log("webservice is not defined");
            }else{
                webservice.run();
            }
        });
    </script>
{/block}