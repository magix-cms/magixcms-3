{$data = $holder}
{if isset($data) && !empty($data)}
    <div class="row">
        {foreach $data as $key => $value}
                {if is_array($value)}
                <div class="col-ph-12">
                    <h4 class="text-center">{#$key#|ucfirst}</h4>
                    <figure class="thumbnail">
                        <div class="center-img">
                            <img class="img-responsive" src="/img/default/{$key}/{$value.img.filename}?{$smarty.now}" />
                        </div>
                        <figcaption>
                            <div class="desc">
                                <h3>{$value.img.width} X {$value.img.height}</h3>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                {/if}
        {/foreach}
    </div>
{/if}