{autoload_i18n}
{switch $message}
{case 'config_success' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success_config#}{/capture}
{case 'request_success' break}
{capture name="alert_type"}success{/capture}
{capture name="icon"}check{/capture}
{capture name="alert_message"}{#request_success#}{/capture}
{case 'config_error' break}
{capture name="alert_type"}danger{/capture}
{capture name="icon"}exclamation-triangle{/capture}
{capture name="alert_message"}{#request_warning_config#}{/capture}
{case 'connexion_impossible' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}exclamation-triangle{/capture}
{capture name="alert_message"}{#request_warning_db#}{/capture}
{case 'request_missing' break}
{capture name="alert_type"}warning{/capture}
{capture name="icon"}exclamation-triangle{/capture}
{capture name="alert_message"}{#request_missing#}{/capture}
{/switch}
<p class="col-sm-12 alert alert-{$smarty.capture.alert_type} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="fas fa-{$smarty.capture.icon} fa-lg"></span> {$smarty.capture.alert_message}
</p>