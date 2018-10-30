{autoload_i18n}
{switch $message}
{case 'add' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_add#}{/capture}
    {** Add redirect **}
{case 'add_redirect' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{strip}
    {#request_success_add_redirect#}
    <i class="fa fa-spinner fa-pulse fa-fw"></i>
    <span class="sr-only">Redirection...</span>
{/strip}{/capture}
    {** Update **}
{case 'update' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_update#}{/capture}
{case 'refresh_lang' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_refresh#}{/capture}
    {** Upload **}
{case 'upload' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_update#}{/capture}
    {** Mail **}
{case 'send' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_send#}{/capture}
{case 'request' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_request#}{/capture}
    {** Delete **}
{case 'delete' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_delete#}{/capture}
{case 'delete_multi' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_delete_m#}{/capture}
{case 'delete_min' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_min#}{/capture}
    {** Test Remote data **}
{case 'remote_data' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_remote_data#}{/capture}
    {** Install **}
{case 'setup_success' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_setup#}{/capture}
{case 'setup_info' break}
{capture name="alert_type"}info{/capture}
{capture name="icon"}info-circle{/capture}
{capture name="alert_message"}{#request_info_setup#}{/capture}
{case 'setup_error' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_setup#}{/capture}
{case 'upgrade_succes' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_upgrade#}{/capture}
{case 'upgrade_empty' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_empty_upgrade#}{/capture}
    {********* Warning *********}
    {** Empty **}
{case 'empty' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_empty#}{/capture}
    {** Email **}
{case 'email' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_email#}{/capture}
    {** lang_exist **}
{case 'lang_exist' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_lang_exist#}{/capture}
    {** lang_default **}
{case 'lang_default' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_lang_default#}{/capture}
{case 'country_exist' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_country_exist#}{/capture}
{case 'child_exist' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_child_exist#}{/capture}
{case 'cannot_delete' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_delete#}{/capture}
{case 'cannot_multiple_delete' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_multiple_delete#}{/capture}
{case 'no_images' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_no_images#}{/capture}
{case 'error_writable' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_writable#}{/capture}
{case 'error_mail_account' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_mail_account#}{/capture}
    {********* Error *********}
    {** error_login **}
{case 'error_login' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_login#}{/capture}
    {** error_hash **}
{case 'error_hash' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_hash#}{/capture}
    {** access_denied **}
{case 'access_denied' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_access_denied#}{/capture}
    {** access_error **}
{case 'access_error' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_access_error#}{/capture}
    {** error_remote **}
{case 'error_remote' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_remote#}{/capture}
    {** upload_error **}
{case 'upload_error' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_upload#}{/capture}
    {********* Plugins *********}
    {** error_login **}
{case 'error_plugin' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#plugin_error#}{/capture}
    {** error_hash **}
{case 'error_plugin_installed' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#plugin_error_installed#}{/capture}
    {** access_denied **}
{case 'error_plugin_configured' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#plugin_error_configured#}{/capture}
{/switch}
<p class="{if $message neq 'error_login' AND $message neq 'error_hash'}col-sm-12{/if} alert alert-{$smarty.capture.alert_type} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="fa fa-{$smarty.capture.icon} fa-lg"></span> {$smarty.capture.alert_message}
</p>