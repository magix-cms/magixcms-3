{if $message.type eq 'error'}
    <p class="col-sm-12 alert alert-warning fade in">
        {*<button type="button" class="close" data-dismiss="alert">&times;</button>*}
        <i class="material-icons ico ico-check"></i> {$message.content}
    </p>
{else}
    <p class="col-sm-12 alert alert-success fade in">
        {*<button type="button" class="close" data-dismiss="alert">&times;</button>*}
        <i class="material-icons ico ico-check"></i> {$message.content}
    </p>
{/if}
