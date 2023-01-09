{$data = $holder}
{if isset($data) && !empty($data)}
    {*<div class="row">
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
    </div>*}
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <td>Module</td>
                <td>Item</td>
                <td>Size</td>
                <td>Name</td>
                <td>Dimensions</td>
            </tr>
            </thead>
            <tbody>
            {foreach $data as $module => $attributes}
                {foreach $attributes as $attribute => $sizes}
                    {foreach $sizes as $imageSize}
                        <tr>
                            <td>{$module|ucfirst}</td>
                            <td>{$attribute|ucfirst}</td>
                            <td>{$imageSize.type|ucfirst}</td>
                            <td>{$imageSize.prefix}_default</td>
                            <td>{$imageSize.width} x {$imageSize.height}</td>
                        </tr>
                    {/foreach}
                {/foreach}
            {/foreach}
            </tbody>
        </table>
    </div>
{/if}