<div id="cookies" class="fade in hide" role="alert">
    <button type="button" class="btn btn-box btn-invert-white pull-right">{#close_cookie#|ucfirst}</button>
    <p>
        {capture name="cookieLink"}<a href="{#cookie_page#}" class="targetblank bold-link">{#cookie_read_page#|ucfirst}</a>{/capture}
        <i class="material-icons ico ico-info_outline">info_outline</i> {#cookie_text#|sprintf:$smarty.capture.cookieLink}
    </p>
</div>