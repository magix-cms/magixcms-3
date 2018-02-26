{autoload_i18n}
{switch $message}
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
{/switch}
<p class="alert alert-{$smarty.capture.alert_type} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="fa fa-{$smarty.capture.icon} fa-lg"></span> {$smarty.capture.alert_message}
</p>