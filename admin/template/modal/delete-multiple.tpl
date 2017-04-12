{if !isset($info_text)}
    {$info_text = true}
{/if}
{if !isset($title)}
    {$title = {#modal_multiple_delete_title#}}
{/if}
{*-- Modal --*}
<div class="modal fade" id="delete_multiple_modal" tabindex="-1" role="dialog" aria-labelledby="deleteMultipleModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$title}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <p><span class="fa fa-warning"></span> <strong>{#warning#}&thinsp;!</strong> {#modal_multiple_delete_message#}</p>
                </div>
                {if $info_text}
                    <div class="help-block">{#modal_multiple_delete_info#}</div>
                {/if}
            </div>
            <div class="modal-footer">
                <form class="delete-form" action="#" method="post">
                    <input type="hidden" name="id" value="">
                    <button type="button" class="btn btn-info" data-dismiss="modal">{#cancel#|ucfirst}</button>
                    <button type="submit" name="delete-multiple" value="{$data_type}" class="btn btn-danger">{#remove#|ucfirst}</button>
                </form>
            </div>
        </div>
    </div>
</div>