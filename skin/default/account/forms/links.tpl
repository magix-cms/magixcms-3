<form id="links-form" method="post" action="{$smarty.server.REQUEST_URI}">
    {$socials = ['website','facebook','twitter','google','viadeo','linkedin']}
    <div class="row">
        {foreach $socials as $link}
            {$label = 'pn_account_'|cat:$link}
            {$placeholder = 'ph_account_'|cat:$link}
            <div class="form-group col-xs-12 col-sm-6">
                <label for="{$link}_ac">
                    {#$label#|ucfirst}&nbsp;:
                </label>
                <div class="input-group links-icon">
                    <div  class="input-group-addon" id="sizing-addon1">
                        <p>
                            <span>
                                {*<span class="logo-icon icon-{$link}">
                                    <img src="/skin/{template}/img/share/socials.png" alt="{$item.name|ucfirst}" width="60" height="365"/>
                                </span>*}
                                {if $link == 'google'}
                                    {$icon = $link|cat:'-plus'}
                                {elseif $link == 'website'}
                                    {$icon = 'globe'}
                                {else}
                                    {$icon = $link}
                                {/if}
                                <span class="logo-icon fa fa-2x fa-inverse fa-{$icon}"></span>
                            </span>
                        </p>
                    </div>
                    <input id="{$link}_ac" type="text" name="{$link}_ac" value="{$data[{$link}]}" placeholder="{#$placeholder#|ucfirst}" class="form-control" aria-describedby="sizing-addon1"/>
                </div>
            </div>
        {/foreach}
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-box btn-flat btn-main-theme" value="{#pn_account_save#|ucfirst}" />
    </div>
    <div class="clearfix mc-message-link"></div>
</form>