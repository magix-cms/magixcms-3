<amp-user-notification
        layout=nodisplay
        id="amp-cookies"
        data-persist-dismissal="false"
        data-show-if-href="{geturl|replace:'http://':'//'}/cookie/?timestamp=TIMESTAMP"
        data-dismiss-href="{geturl|replace:'http://':'//'}/cookie/">
    {#cookie_text#} <a href="{#cookie_page#}" class="targetblank bold-link">{#cookie_read_page#|ucfirst}</a>
    <a class="btn btn-box btn-invert-white" on="tap:amp-cookies.dismiss">{#close_cookie#|ucfirst}</a>
</amp-user-notification>
</amp-analytics>