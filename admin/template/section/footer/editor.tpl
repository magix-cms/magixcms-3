<script type="text/javascript">
    {capture name="tinyMCEstyleSheet"}/{baseadmin}/template/css/tinymce-content.css,{/capture}
    var content_css = "{if $setting.content_css.value != ''}{$smarty.capture.tinyMCEstyleSheet}{$setting.content_css.value}{/if}";
</script>
{capture name="scriptTinyMCE"}{strip}
    /{baseadmin}/min/?g=tinymce
{/strip}{/capture}
{script src=$smarty.capture.scriptTinyMCE type="javascript"}