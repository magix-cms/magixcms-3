<amp-user-notification
        layout=nodisplay
        id="amp-cookies"
        data-persist-dismissal="false"
        data-show-if-href="{*$url|replace:'http://':'//'*}{$url}/cookie/?timestamp=TIMESTAMP"
        data-dismiss-href="{*$url|replace:'http://':'//'*}{$url}/cookie/">
    {capture name="cookieLink"}<a href="{#cookie_page#}" class="targetblank bold-link">{#cookie_read_page#|ucfirst}</a>{/capture}
    <i class="material-icons">info_outline</i> {#cookie_text#|sprintf:$smarty.capture.cookieLink}
    <a class="btn btn-box btn-invert-white" on="tap:amp-cookies.dismiss">{#close_cookie#|ucfirst}</a>
</amp-user-notification>
</amp-analytics>