{autoload_i18n}
{switch $message}
{********* Success *********}
{case 'add' break}
    {** Add **}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_add#}
{/capture}
{case 'add_redirect' break}
    {** Add **}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_add_redirect#}
    <i class="fa fa-spinner fa-pulse fa-fw"></i>
    <span class="sr-only">Redirection...</span>
{/capture}
    {** Update **}
{case 'update' break}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_update#}
{/capture}
    {** Upload **}
{case 'upload' break}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_update#}
{/capture}
    {** Delete **}
{case 'delete' break}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_delete#}
{/capture}
    {** Test Remote data **}
{case 'remote_data' break}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_remote_data#}
{/capture}
    {** Install **}
{case 'setup_succes' break}
{capture name="alert_type"}{strip}
    success
{/strip}{/capture}
{capture name="icon"}{strip}
    check
{/strip}{/capture}
{capture name="alert_message"}
    {#request_success_setup#}
{/capture}
{case 'setup_info' break}
{capture name="alert_type"}{strip}
    info
{/strip}{/capture}
{capture name="icon"}{strip}
    info-circle
{/strip}{/capture}
{capture name="alert_message"}
    {#request_info_setup#}
{/capture}
{********* Warning *********}
    {** Empty **}
{case 'empty' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_empty#}
{/capture}
    {** lang_exist **}
{case 'lang_exist' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_lang_exist#}
{/capture}
    {** lang_default **}
{case 'lang_default' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_lang_default#}
{/capture}
{case 'country_exist' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_country_exist#}
{/capture}
{case 'child_exist' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_child_exist#}
{/capture}
{case 'cannot_delete' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_delete#}
{/capture}
{case 'cannot_multiple_delete' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_multiple_delete#}
{/capture}
{case 'no_images' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_no_images#}
{/capture}
{case 'error_writable' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_writable#}
{/capture}
{case 'error_mail_account' break}
{capture name="alert_type"}{strip}
    warning
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_mail_account#}
{/capture}
{********* Error *********}
    {** error_login **}
{case 'error_login' break}
{capture name="alert_type"}{strip}
    danger
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_login#}
{/capture}
{case 'error_hash' break}
{capture name="alert_type"}{strip}
    danger
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_hash#}
{/capture}
    {** access_denied **}
{case 'access_denied' break}
{capture name="alert_type"}{strip}
    danger
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_access_denied#}
{/capture}
    {** access_error **}
{case 'access_error' break}
{capture name="alert_type"}{strip}
    danger
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_access_error#}
{/capture}
    {** error_remote **}
{case 'error_remote' break}
{capture name="alert_type"}{strip}
    danger
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_remote#}
{/capture}
    {** upload_error **}
{case 'upload_error' break}
{capture name="alert_type"}{strip}
    danger
{/strip}{/capture}
{capture name="icon"}{strip}
    warning
{/strip}{/capture}
{capture name="alert_message"}
    {#request_error_upload#}
{/capture}
{/switch}
<p class="{if $message neq 'error_login' AND $message neq 'error_hash'}col-sm-12{/if} alert alert-{$smarty.capture.alert_type} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="fa fa-{$smarty.capture.icon} fa-lg"></span> {$smarty.capture.alert_message}
</p>