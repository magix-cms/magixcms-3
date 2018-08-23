{if !empty($addresses)}
    {if isset($page) && !empty($page)}
        <article class="container">
            <h1{if empty($page.content_gmap)} class="sr-only"{/if}>
                {if isset($page.name_gmap) && !empty($page.name_gmap)}
                    {$page.name_gmap}
                {else}
                    {#access_plan#}
                {/if}
            </h1>
            {if isset($page.content_gmap) && !empty($page.content_gmap)}
                <div class="gmap-content">
                    {$page.content_gmap}
                </div>
            {/if}
        </article>
    {/if}
    <div class="map">
        <div>
            <div id="map_adress" class="gmap3"></div>
        </div>
        <div id="gmap-address" class="open">
            <div id="searchdir" class="collapse">
                <form class="form-search">
                    <div class="input-group">
                        <input type="text" class="form-control" id="getadress" name="getadress" placeholder="{#gmap_adress#}" value="" />
                        <div class="input-group-btn">
                            <button class="btn btn-default subdirection" type="submit">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="alert alert-primary" itemscope itemtype="http://data-vocabulary.org/Organization">
                <a id="showform" class="btn btn-lg pull-right collapsed hidden-ph hidden-xs" type="button" data-toggle="collapse" data-target="#searchdir" aria-expanded="false" aria-controls="searchdir">
                    <span class="open"><i class="material-icons">directions</i></span>
                    <span class="close"><i class="material-icons">close</i></span>
                </a>
                {strip}<a id="openapp" class="btn btn-lg pull-right visible-ph visible-xs"
                   {if $mOS === 'IOS'} href="http://maps.apple.com/maps?ll={$addresses[0].lat_address},{$addresses[0].lng_address}&q={$addresses[0].address_address|escape:'url'}%2C%20{$addresses[0].city_address|escape:'url'}%2C%20{$addresses[0].country_address|escape:'url'}"
                   {else} href="geo:{$addresses[0].lat_address},{$addresses[0].lng_address}?q={$addresses[0].address_address|escape:'url'}%2C%20{$addresses[0].city_address|escape:'url'}%2C%20{$addresses[0].country_address|escape:'url'}"{/if} target="_blank">
                    <i class="material-icons">directions</i>
                </a>{/strip}
                    <button class="btn btn-default btn-box hidepanel open">
                        <span class="show-less ver"><i class="material-icons">keyboard_arrow_up</i></span>
                        <span class="show-more ver"><i class="material-icons">keyboard_arrow_down</i></span>
                        <span class="show-less hor"><i class="material-icons">keyboard_arrow_left</i></span>
                        <span class="show-more hor"><i class="material-icons">keyboard_arrow_right</i></span>
                    </button>
                <meta itemprop="name" content="{$addresses[0].company_address}" />
                <div id="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                    <span class="fa fa-map-marker"></span>
                    <span class="address" itemprop="streetAddress">{$addresses[0].address_address}</span>,
                    <span itemprop="addressLocality">
                        <span class="city">{$addresses[0].postcode_address} {$addresses[0].city_address}</span>, <span class="country">{$addresses[0].country_address}</span>
                    </span>
                    <div itemprop="address" itemscope itemtype="http://schema.org/GeoCoordinates">
                        <meta itemprop="latitude" content="{$addresses[0].lat_address}" />
                        <meta itemprop="longitude" content="{$addresses[0].lng_address}" />
                    </div>
                </div>
            </div>
            <div id="r-directions"></div>
        </div>
    </div>

    {if count($addresses) > 1}
        <div id="addresses" class="container">
            {foreach $addresses as $addr}
                {if ($addr@index)%2 == 0}
                    <div class="row">
                {/if}
                <div class="col-12 col-sm-6 col-md-4 col-lg-6">
                    {capture name="content"}
                        <h3>{$addr.company_address}</h3>
                        <p>{$addr.address_address}, {$addr.postcode_address} {$addr.city_address}, {$addr.country_address}</p>
                        {if $addr.about_address}<p>{$addr.about_address}</p>{/if}
                        <p>
                            {if $addr.link_address}<a href="{$addr.link_address}" class="btn btn-box btn-invert btn-main-theme"">{#more_infos#}</a>{/if}
                            <a href="#" class="btn btn-box btn-invert btn-main-theme select-marker" data-marker="{$addr@index}">{#see_on_map#}</a>
                        </p>
                    {/capture}
                    {if !empty($addr.img_address)}
                        <div class="row">
                            <div class="col-12 col-xs-6 col-sm-12 col-lg-6">
                                <img class="img-responsive" src="{$url}/upload/gmap/{$addr.id_address}/{$addr.img_address}" alt="{$addr.company}">
                            </div>
                            <div class="col-12 col-xs-6 col-sm-12 col-lg-6">
                                {$smarty.capture.content}
                            </div>
                        </div>
                    {else}
                        {$smarty.capture.content}
                    {/if}
                </div>
                {if ($addr@index +1)%2 == 0 || {$addr@last}}
                    </div>
                {/if}
            {/foreach}
        </div>
    {/if}
{else}
    <div class="container">
        <div class="mc-message clearfix">
            <p class="alert alert-warning fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span class="fa fa-warning-sign"></span> {#gmap_plugin_error#} : {#gmap_plugin_configured#}
            </p>
        </div>
    </div>
{/if}