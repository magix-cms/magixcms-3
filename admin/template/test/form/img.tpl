<form method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" enctype="multipart/form-data">
    <div class="form-group">
        <label for="img">Image :</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="2048576" />
        <input type="file" id="img" name="img" value="" />
    </div>
    <div class="form-group">
        <input type="submit" value="send" />
    </div>
</form>
<form method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" enctype="multipart/form-data">
    <div class="form-group">
        <label for="img_multiple">Image Multiple :</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="4048576" />
        <input type="file" name="img_multiple[]" id="img_multiple" value="" multiple />
    </div>
    <div class="form-group">
        <input type="submit" value="send" />
    </div>
</form>
{*
<form method="post" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" enctype="multipart/form-data">
    <div class="form-group">
        <label for="file">Fichier PDF :</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="2048576" />
        <input type="file" id="file" name="file" value="" />
    </div>
    <div class="form-group">
        <input type="submit" value="send" />
    </div>
</form>*}
