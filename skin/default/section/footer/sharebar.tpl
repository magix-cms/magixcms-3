<div class="visible-lg sharebar">
    {if $adjust == 'clip'}
    <div class="container">
        {/if}
        <div class="sharebox">
            {*<span class="label">{#share#|ucfirst}&nbsp;:</span>*}
            <i class="material-icons">share</i>
            <ul class="list-unstyled list-inline share-nav">
                {include file="section/loop/share.tpl" data=$shareUrls config=$shareConfig}
            </ul>
        </div>
        <div class="followbox pull-right">
            <span class="label">{#follow_us#|ucfirst}&nbsp;:</span>
            <ul class="list-unstyled list-inline">
                {if $companyData.socials.facebook != null}
                    <li>
                        <a href="{$companyData.socials.facebook}" title="{#fb_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-facebook"></span><span class="sr-only">{#fb_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter != null}
                    <li>
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-twitter"></span><span class="sr-only">{#tw_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.google != null}
                    <li>
                        <a href="{$companyData.socials.google}" title="{#gg_follow_title#|ucfirst}" rel="publisher" class="targetblank">
                            <span class="fa fa-google-plus"></span><span class="sr-only">{#gg_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin != null}
                    <li>
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-linkedin"></span><span class="sr-only">{#lk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.viadeo != null}
                    <li>
                        <a href="{$companyData.socials.viadeo}" title="{#vi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-viadeo"></span><span class="sr-only">{#vi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.pinterest != null}
                    <li>
                        <a href="{$companyData.socials.pinterest}" title="{#pi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-pinterest"></span><span class="sr-only">{#pi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.instagram != null}
                    <li>
                        <a href="{$companyData.socials.instagram}" title="{#ig_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-instagram"></span><span class="sr-only">{#ig_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.github != null}
                    <li>
                        <a href="{$companyData.socials.github}" title="{#gh_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-github"></span><span class="sr-only">{#gh_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.soundcloud != null}
                    <li>
                        <a href="{$companyData.socials.soundcloud}" title="{#sc_follow_title#|ucfirst}" class="targetblank">
                            <span class="fa fa-soundcloud"></span><span class="sr-only">{#sc_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
        {if $adjust == 'clip'}
    </div>
    {/if}
</div>