<div id="block-socials" class="col block">
    {if !empty($companyData.socials)}
        <div class="followbox">
            <p>{#follow#|ucfirst}</p>
            <ul class="list-unstyled list-inline">
                {*{if $companyData.socials.facebook !== null}
                    <li>
                        <a href="{$companyData.socials.facebook}" title="{#fb_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-facebook-f"></span><span class="sr-only">{#fb_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.twitter !== null}
                    <li>
                        <a href="{$companyData.socials.twitter}" title="{#tw_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-twitter"></span><span class="sr-only">{#tw_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.linkedin !== null}
                    <li>
                        <a href="{$companyData.socials.linkedin}" title="{#lk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-linkedin-in"></span><span class="sr-only">{#lk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.viadeo !== null}
                    <li>
                        <a href="{$companyData.socials.viadeo}" title="{#vi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-viadeo"></span><span class="sr-only">{#vi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.pinterest !== null}
                    <li>
                        <a href="{$companyData.socials.pinterest}" title="{#pi_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-pinterest-p"></span><span class="sr-only">{#pi_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.instagram !== null}
                    <li>
                        <a href="{$companyData.socials.instagram}" title="{#ig_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-instagram"></span><span class="sr-only">{#ig_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.github !== null}
                    <li>
                        <a href="{$companyData.socials.github}" title="{#gh_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-github"></span><span class="sr-only">{#gh_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.soundcloud !== null}
                    <li>
                        <a href="{$companyData.socials.soundcloud}" title="{#sc_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-soundcloud"></span><span class="sr-only">{#sc_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.youtube !== null}
                    <li>
                        <a href="{$companyData.socials.youtube}" title="{#yt_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-youtube"></span><span class="sr-only">{#yt_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.tumblr !== null}
                    <li>
                        <a href="{$companyData.socials.tumblr}" title="{#tr_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-tumblr"></span><span class="sr-only">{#tr_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}
                {if $companyData.socials.tiktok !== null}
                    <li>
                        <a href="{$companyData.socials.tiktok}" title="{#tk_follow_title#|ucfirst}" class="targetblank">
                            <span class="fab ico ico-tiktok"></span><span class="sr-only">{#tk_follow_label#|ucfirst}</span>
                        </a>
                    </li>
                {/if}*}
                {foreach $companyData.socials as $name => $link}
                    {if $link.url !== null}
                        {$title = $name|cat:'_follow_title'}
                        {$label = $name|cat:'_follow_label'}
                        <li>
                            <a href="{$link.url}" title="{#$title#}" class="targetblank">
                                <span class="fab ico ico-{$name}"></span><span class="sr-only">{#$label#}</span>
                            </a>
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>
    {/if}
</div>