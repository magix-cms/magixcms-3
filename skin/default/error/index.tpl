{extends file="layout.tpl"}
{block name='body:id'}error{/block}
{block name="title"}{$getTitleHeader}{/block}
{block name="description"}{$getTxtHeader}{/block}
{block name="breadcrumb"}{/block}
{block name='article:content'}
    <p class="h1 text-center"><strong>{#oops#}</strong></p>
    <p class="h2 text-center">{#page_not_found#}</p>
    <div class="text-center">
        {*<p class="help-block">error code : {$error_code}</p>*}
        <form id="contact-form" class="validate_form button_feedback" method="post" action="{geturl}/{getlang}/contact/">
            <input type="hidden" name="msg[email]" value="error-mail" class="required" required/>
            <input type="hidden" name="msg[title]" value="Tracking errors" class="required" required/>
            <input type="hidden" name="msg[error]" value="{$error_code}" class="required" required/>
            <input type="hidden" name="msg[content]" value="{geturl}{$smarty.server.REQUEST_URI}" class="required" required>
            <input type="hidden" name="msg[moreinfo]" value="" />
            <p class="lead">{#report_link#}<br><button type="submit" class="btn btn-box btn-link">{#report#} <i class="material-icons">error_outline</i></button></p>
        </form>
        <p class="lead text-success success hide">{#thanks#} <i class="material-icons">check</i></p>
        <ul class="link-bar">
            {foreach $links as $k => $link}
            <li>
                <a href="{$link.url_link}" title="{if empty($link.title_link)}{$link.name_link}{else}{$link.title_link}{/if}">
                    <span>{$link.name_link}</span>
                </a>
            </li>
            {/foreach}
        </ul>
    </div>
{/block}

{block name="foot"}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {script src="/min/?f=skin/{template}/js/form.min.js" concat=$concat type="javascript"}
    {if {getlang} !== "en"}
        {script src="/min/?f=libjs/vendor/localization/messages_{getlang}.js" concat=$concat type="javascript"}
    {/if}
    <script type="text/javascript">
        $(function(){
            if (typeof globalForm == "undefined")
            {
                console.log("globalForm is not defined");
            }else{
                globalForm.run();
            }
        });
    </script>
{/block}