{$data = ['imgSrc' =>  $page.imgSrc]}
{if isset($data) && !empty($data)}
    <div class="row">
        {foreach $data.imgSrc as $key => $value}
            {if $key != 'original'}
                <div class="col-ph-12">
                    <figure class="thumbnail">
                        <div class="center-img">
                            <img class="img-responsive" src="/img/{if isset($uploadDir)}{$uploadDir}{else}{$smarty.get.controller}{/if}/{$value.img}?{$smarty.now}" />
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
    </div>
{/if}