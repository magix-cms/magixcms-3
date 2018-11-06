<div class="sharebar">
    {if $adjust == 'clip'}
    <div class="container">
        {/if}
        <div class="followbox">
            <p class="label">{#follow_us#|ucfirst}</p>
            <ul class="list-inline">
                {if $companyData.socials.facebook != null}
                    <li class="share-facebook">
                        <a href="{$companyData.socials.facebook}" title="{#fb_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-facebook-f"></span><span class="sr-only">{#fb_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter != null}
                    <li class="share-twitter">
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-twitter"></span><span class="sr-only">{#tw_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.google != null}
                    <li class="share-google">
                        <a href="{$companyData.socials.google}" title="{#gg_follow_title#|ucfirst}" rel="publisher" class="targetblank">
                            <span class="fab fa-google-plus-g"></span><span class="sr-only">{#gg_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin != null}
                    <li class="share-linkedin">
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-linkedin-in"></span><span class="sr-only">{#lk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.viadeo != null}
                    <li class="share-viadeo">
                        <a href="{$companyData.socials.viadeo}" title="{#vi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-viadeo"></span><span class="sr-only">{#vi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.pinterest != null}
                    <li class="share-pinterest">
                        <a href="{$companyData.socials.pinterest}" title="{#pi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-pinterest-p"></span><span class="sr-only">{#pi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.instagram != null}
                    <li class="share-instagram">
                        <a href="{$companyData.socials.instagram}" title="{#ig_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-instagram"></span><span class="sr-only">{#ig_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.github != null}
                    <li class="share-github">
                        <a href="{$companyData.socials.github}" title="{#gh_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-github"></span><span class="sr-only">{#gh_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.soundcloud != null}
                    <li class="share-soundcloud">
                        <a href="{$companyData.socials.soundcloud}" title="{#sc_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab fa-soundcloud"></span><span class="sr-only">{#sc_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
        {if $adjust == 'clip'}
    </div>
    {/if}
</div>