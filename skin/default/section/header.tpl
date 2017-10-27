<header id="header" role="navigation"{if !$touch}class="at-top"{/if}>
    {* Show Nav Button (xs ad sm only) *}
    <button type="button" class="toggle-menu navbar-toggle" data-toggle="collapse" data-target="#menu">
        <i class="material-icons">menu</i>
        <span class="sr-only">{#openNavigation#|ucfirst}</span>
    </button>
    {* Brand && Headline *}
    <div id="navbar-brand">
        <a href="{geturl}/{getlang}/" title="{#logo_link_title#|ucfirst}">
            {strip}{capture name="sizes"}
                (min-width: 1500px) 5vw,
                (min-width: 992px) 8vw,
                (orientation: portrait) and (min-width: 768px) 15vw,
                (orientation: landscape) and (min-width: 768px) 10vw,
                (orientation: portrait) and (min-width: 480px) 25vw,
                80px
            {/capture}
            <picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            <source type="image/webp"
                    sizes="{$smarty.capture.sizes}"
                    srcset="{geturl}/skin/{template}/img/logo/webp/{#logo_img#}@229.webp 229w,
                                {geturl}/skin/{template}/img/logo/webp/{#logo_img#}@480.webp 480w">
            <source sizes="{$smarty.capture.sizes}"
                    srcset="{geturl}/skin/{template}/img/logo/png/{#logo_img#}@229.png 229w,
                                {geturl}/skin/{template}/img/logo/png/{#logo_img#}@480.png 480w">
            <!--[if IE 9]></video><![endif]-->
            <img src="{geturl}/skin/{template}/img/logo/png/{#logo_img#}@229.png"
                 sizes="{$smarty.capture.sizes}"
                 srcset="{geturl}/skin/{template}/img/logo/png/{#logo_img#}@229.png 229w,
                            {geturl}/skin/{template}/img/logo/png/{#logo_img#}@480.png 480w"
                 alt="{#logo_img_alt#|ucfirst} {$companyData.name}" />
            </picture>{/strip}
        </a>
    </div>
    {if $dataLang != null && count($dataLang) > 1}
        <div class="select-lang">
            {include file="section/brick/lang.tpl" display='menu'}
        </div>
    {/if}
    {include file="section/menu/primary.tpl" type="dropdown" root=['home'=>false,'about'=>true,'catalog'=>true,'news'=>false,'contact'=>true] main=true submenu=true gmap=true mobile=false}
    {if $viewport !== 'mobile'}
        <div class="followbox visible-lg">
            <span class="sr-only">{#follow_us#|ucfirst}</span>
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
    {/if}
</header>
{include file="section/menu/primary.tpl" type="dropdown" root=['home'=>true,'about'=>true,'catalog'=>true,'news'=>true,'contact'=>true] main=false submenu=true gmap=true mobile=true}