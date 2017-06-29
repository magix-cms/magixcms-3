{if isset($data) && !empty($data)}
    {foreach $data.imgSrc as $key => $value}
        {if $key != 'original'}
        <div class="col-md-4">
            <figure class="thumbnail">
                <div class="center-img">
                    <img class="img-responsive" src="/upload/{$smarty.get.controller}/{$data.id}/{$value.img}" />
                </div>
                <figcaption>
                    <div class="desc">
                        <h3>{$value.width} X {$value.height}</h3>
                    </div>
                </figcaption>
            </figure>
        </div>
        {/if}
    {/foreach}
{/if}