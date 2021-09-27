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
                            <span class="fab ico ico-facebook-f"></span><span class="sr-only">{#fb_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter != null}
                    <li class="share-twitter">
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-twitter"></span><span class="sr-only">{#tw_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.youtube != null}
                    <li class="share-youtube">
                        <a href="{$companyData.socials.youtube}" title="{#yt_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-youtube"></span><span class="sr-only">{#yt_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin != null}
                    <li class="share-linkedin">
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-linkedin-in"></span><span class="sr-only">{#lk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.viadeo != null}
                    <li class="share-viadeo">
                        <a href="{$companyData.socials.viadeo}" title="{#vi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-viadeo"></span><span class="sr-only">{#vi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.pinterest != null}
                    <li class="share-pinterest">
                        <a href="{$companyData.socials.pinterest}" title="{#pi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-pinterest-p"></span><span class="sr-only">{#pi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.instagram != null}
                    <li class="share-instagram">
                        <a href="{$companyData.socials.instagram}" title="{#ig_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-instagram"></span><span class="sr-only">{#ig_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.github != null}
                    <li class="share-github">
                        <a href="{$companyData.socials.github}" title="{#gh_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-github"></span><span class="sr-only">{#gh_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.soundcloud != null}
                    <li class="share-soundcloud">
                        <a href="{$companyData.socials.soundcloud}" title="{#sc_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-soundcloud"></span><span class="sr-only">{#sc_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.tumblr != null}
                    <li class="share-tumblr">
                        <a href="{$companyData.socials.tumblr}" title="{#tr_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-tumblr"></span><span class="sr-only">{#tr_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.tiktok != null}
                    <li class="share-tiktok">
                        <a href="{$companyData.socials.tiktok}" title="{#tk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-tiktok"></span><span class="sr-only">{#tk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
        {if $adjust == 'clip'}
    </div>
    {/if}
</div>