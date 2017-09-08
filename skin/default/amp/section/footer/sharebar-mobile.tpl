<div id="sharebar">
    {if $adjust == 'clip'}
    <div class="container">
        {/if}
        <div class="followbox">
            <span class="label">{#follow_us#|ucfirst}&nbsp;:</span>
            <ul class="list-unstyled list-inline">
                {if $companyData.socials.facebook != null}
                    <li>
                        <a href="{$companyData.socials.facebook}" title="{#fb_follow_title#|ucfirst}">
                            <span class="fa fa-facebook"></span><span class="sr-only">{#fb_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter != null}
                    <li>
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}">
                            <span class="fa fa-twitter"></span><span class="sr-only">{#tw_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.google != null}
                    <li>
                        <a href="{$companyData.socials.google}" title="{#gg_follow_title#|ucfirst}" rel="publisher">
                            <span class="fa fa-google-plus"></span><span class="sr-only">{#gg_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin != null}
                    <li>
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}">
                            <span class="fa fa-linkedin"></span><span class="sr-only">{#lk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
        {if $adjust == 'clip'}
    </div>
    {/if}
</div>