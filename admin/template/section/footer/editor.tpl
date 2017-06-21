<script type="text/javascript">
    {capture name="tinyMCEstyleSheet"}/{baseadmin}/template/css/tinymce-content.css,{/capture}
    content_css = "{$smarty.capture.tinyMCEstyleSheet}";
</script>
{capture name="scriptTinyMCE"}{strip}
    /{baseadmin}/min/?g=tinymce
{/strip}{/capture}
{script src=$smarty.capture.scriptTinyMCE type="javascript"}