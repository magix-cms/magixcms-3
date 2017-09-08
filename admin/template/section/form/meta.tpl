{if isset($page)}{$content = $page.content[{$id}]}{/if}
<div class="form-group">
    <label for="content[{$id}][seo_title_{$type}]">{#seo_title#|ucfirst} :</label>
    <input type="text" class="form-control" id="content[{$id}][seo_title_{$type}]" name="content[{$id}][seo_title_{$type}]" value="{if isset($page)}{$content['seo_title_'|cat:$type]}{/if}"/>
</div>
<div class="form-group">
    <label for="content[{$id}][seo_desc_{$type}]">{#seo_desc#|ucfirst} :</label>
    <textarea class="form-control" id="content[{$id}][seo_desc_{$type}]" name="content[{$id}][seo_desc_{$type}]" cols="70" rows="3">{if isset($page)}{$content['seo_desc_'|cat:$type]}{/if}</textarea>
</div>