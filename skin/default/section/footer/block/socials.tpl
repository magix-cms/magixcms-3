<div id="block-socials" class="col-8 col-xs-4 col-md col-xl-2 block">
    {if !empty($companyData.socials)}
        <div class="followbox">
            <h4>{#follow#|ucfirst}</h4>
            <ul class="list-unstyled list-inline">
                {if $companyData.socials.facebook !== null}
                    <li>
                        <a href="{$companyData.socials.facebook}" title="{#fb_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-facebook-f"></span>{#fb_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter !== null}
                    <li>
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-twitter"></span>{#tw_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin !== null}
                    <li>
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-linkedin-in"></span>{#lk_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.viadeo !== null}
                    <li>
                        <a href="{$companyData.socials.viadeo}" title="{#vi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-viadeo"></span>{#vi_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.pinterest !== null}
                    <li>
                        <a href="{$companyData.socials.pinterest}" title="{#pi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-pinterest-p"></span>{#pi_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.instagram !== null}
                    <li>
                        <a href="{$companyData.socials.instagram}" title="{#ig_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-instagram"></span>{#ig_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.github !== null}
                    <li>
                        <a href="{$companyData.socials.github}" title="{#gh_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-github"></span>{#gh_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.soundcloud !== null}
                    <li>
                        <a href="{$companyData.socials.soundcloud}" title="{#sc_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-soundcloud"></span>{#sc_follow_label#|ucfirst}
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
    {/if}
</div>