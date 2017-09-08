{if isset($page)}{$content = $page.content[{$id}]}{/if}
<div class="form-group">
    <label for="content[{$id}][content_{$type}]">{#content#|ucfirst} :</label>
    <textarea name="content[{$id}][content_{$type}]" id="content[{$id}][content_{$type}]" class="form-control mceEditor">{call name=cleantextarea field=$content['content_'|cat:$type]}</textarea>
</div>