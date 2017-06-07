{extends file="layout.tpl"}
{block name='head:title'}{#setting#|ucfirst}{/block}
{block name='body:id'}setting{/block}

{block name='article:header'}
    <h1 class="h2">{#setting#|ucfirst}</h1>
{/block}
{block name='article:content'}
{if {employee_access type="append" class_name=$cClass} eq 1}
<div class="panels row">
    <section class="panel col-xs-12 col-md-8">
        {if $debug}
            {$debug}
        {/if}
        <header class="panel-header panel-nav">
            <h2 class="panel-heading h5">{#setting_params#|ucfirst}</h2>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#general" aria-controls="info" role="tab" data-toggle="tab">Informations générale</a></li>
                <li role="presentation"><a href="#theme" aria-controls="theme" role="tab" data-toggle="tab">Thème</a></li>
                <li role="presentation"><a href="#google" aria-controls="google" role="tab" data-toggle="tab">Google</a></li>
            </ul>
        </header>
        <div class="panel-body panel-body-form">
            <div class="mc-message-container clearfix">
                <div class="mc-message"></div>
            </div>
            <pre>{$settings|print_r}</pre>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general">
                    {include file="setting/form/general.tpl" controller="setting"}
                </div>
                <div role="tabpanel" class="tab-pane" id="theme">

                </div>
                <div role="tabpanel" class="tab-pane" id="google">

                </div>
            </div>
        </div>
    </section>
</div>
{/if}
{/block}
{block name="foot" append}
    {*{capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        {baseadmin}/template/js/country.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof country == "undefined")
            {
                console.log("country is not defined");
            }else{
                country.runEdit();
            }
        });
    </script>*}
{/block}