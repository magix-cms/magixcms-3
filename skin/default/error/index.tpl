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
        <form id="contact-form" class="validate_form button_feedback" method="post" action="{$url}/{$lang}/contact/">
            <input type="hidden" name="msg[email]" value="error-mail" class="required" required/>
            <input type="hidden" name="msg[title]" value="Tracking errors" class="required" required/>
            <input type="hidden" name="msg[error]" value="{if $error_code}{$error_code}{else}404{/if}" class="required" required/>
            <input type="hidden" name="msg[content]" value="{$url}{$smarty.server.REQUEST_URI}" class="required" required>
            <input type="hidden" name="msg[moreinfo]" value="" />
            <p class="lead">{#report_link#}<br><button type="submit" class="btn btn-box btn-link">{#report#} <i class="material-icons ico ico-error_outline"></i></button></p>
        </form>
        <p class="lead text-success success hide">{#thanks#} <i class="material-icons ico ico-check"></i></p>
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

{block name="scripts"}
    {$jquery = true}
    {$js_files = [
    'group' => ['form'],
    'normal' => [],
    'defer' => ["/skin/{$theme}/js/{if $setting.mode === 'dev'}src/{/if}form{if $setting.mode !== 'dev'}.min{/if}.js"]
    ]}
    {if {$lang} !== "en"}{$js_files['defer'][] = "/libjs/vendor/localization/messages_{$lang}.js"}{/if}
{/block}