{if isset($page)}{$content = $page.content[{$id}]}{/if}
<div class="form-group">
    <label for="content[{$id}][resume_{$type}]">{#resume#|ucfirst} :</label>
    <textarea name="content[{$id}][resume_{$type}]" id="content[{$id}][resume_{$type}]" class="form-control" rows="4">{$content['resume_'|cat:$type]}</textarea>
</div>