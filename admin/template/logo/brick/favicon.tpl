{if isset($favicon) && !empty($favicon)}
    <div class="row">
        <h3>Favicon</h3>
        {foreach $favicon as $key => $value}
                {if is_array($value)}
                {if $value.img.filename != 'fav.png' && $value.img.filename != 'fav.jpg' }
                <div class="col-ph-12">
                    <figure class="thumbnail">
                        <div class="center-img">
                            <img class="img-responsive" src="/img/favicon/{$value.img.filename}?{$smarty.now}" />
                        </div>
                        <figcaption>
                            <div class="desc">
                                <h3>{$value.img.width} X {$value.img.height}</h3>
                            </div>
                        </figcaption>
                    </figure>
                </div>
                {/if}
                {/if}
        {/foreach}
    </div>
    {else}
    <div class="row"></div>
{/if}
{if isset($homescreen) && !empty($homescreen)}
    <div class="row">
        <h3>Homescreen</h3>
        {foreach $homescreen as $key => $value}
            {if is_array($value)}
                <div class="col-ph-12">
                    <figure class="thumbnail">
                        <div class="center-img">
                            <img class="img-responsive" src="/img/touch/{$value.img.filename}?{$smarty.now}" />
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