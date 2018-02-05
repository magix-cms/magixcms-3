{autoload_i18n}{widget_about_data}
{switch $message}
{case 'add' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_add#}{/capture}
{case 'signup' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_signup#|sprintf:$companyData.name}{/capture}
{case 'update' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_update#}{/capture}
{case 'new_password_success' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_newpassword_success#}{/capture}
{case 'pwd_changed' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_pwd_changed#}{/capture}
{** Error **}
{case 'error_connect' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_connect#}{/capture}
{case 'new_password_error' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_email_error#}{/capture}
{case 'empty' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_empty#}{/capture}
{case 'error_email_exist' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_mail_account#}{/capture}
{case 'error_pwd' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_pwd#}{/capture}
{case 'error_captcha' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_captcha#}{/capture}
{case 'signup_error' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_signup#}{/capture}
{case 'error_ticket' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}warning{/capture}
{capture name="alert_message"}{#request_error_ticket#}{/capture}
{/switch}
<p class="alert alert-{$smarty.capture.alert_type} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="fa fa-{$smarty.capture.icon} fa-lg"></span> {$smarty.capture.alert_message}
</p>