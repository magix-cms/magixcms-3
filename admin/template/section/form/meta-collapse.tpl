{if isset($page)}{$content = $page.content[{$id}]}{/if}
<div class="form-group">
    <button class="btn collapsed btn-collapse" role="button" data-toggle="collapse" data-parent="#accordion" href="#metas-{$id}" aria-expanded="true" aria-controls="metas-{$id}">
        <span class="fa"></span> {#display_metas#|ucfirst}
    </button>
</div>
<div id="metas-{$id}" class="collapse" role="tabpanel" aria-labelledby="heading{$id}">
    <div class="row">
        <div class="col-ph-12 col-sm-8">
            <div class="form-group">
                <label for="content[{$id}][seo_title_{$type}]">{#seo_title#|ucfirst} :</label>
                <textarea class="form-control" id="content[{$id}][seo_title_{$type}]" name="content[{$id}][seo_title_{$type}]" cols="70" rows="3">{if isset($page)}{$content['seo_title_'|cat:$type]}{/if}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-ph-12 col-sm-8">
            <div class="form-group">
                <label for="content[{$id}][seo_desc_{$type}]">{#seo_desc#|ucfirst} :</label>
                <textarea class="form-control" id="content[{$id}][seo_desc_{$type}]" name="content[{$id}][seo_desc_{$type}]" cols="70" rows="3">{if isset($page)}{$content['seo_desc_'|cat:$type]}{/if}</textarea>
            </div>
        </div>
    </div>
</div>