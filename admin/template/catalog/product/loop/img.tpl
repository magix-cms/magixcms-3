{if isset($data) && !empty($data)}
    <div class="row">
    {foreach $data as $key => $value}
        <div class="col-md-3">
            <figure class="thumbnail">
                <div class="center-img">
                    <img class="img-responsive" src="/upload/{if isset($uploadDir)}{$uploadDir}{else}{$smarty.get.controller}{/if}/{$value.id_product}/s_{$value.name_img}" />
                </div>
                <figcaption>
                    <div class="desc">
                        {*<h3>{$value.width} X {$value.height}</h3>*}
                        <a href="">test</a>
                    </div>
                </figcaption>
            </figure>
        </div>
    {/foreach}
    </div>
{/if}