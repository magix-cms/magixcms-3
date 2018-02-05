{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>#seo_newpwd_title#]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>#seo_newpwd_desc#]}{/block}
{block name='body:id'}new-password{/block}

{block name="main"}
<main id="content">
    {block name="article:before"}{/block}

    {block name='article'}
    <article id="article" class="container">
        {block name='article:content'}
            <div class="row row-center">
                <div class="col-ph-12 col-sm-6">
                    <div class="content-box">
                        <h1 class="sr-only">{#newpwd_h1#|ucfirst}</h1>
                        {$message}
                        <p class="lead text-center">{#newpwd_connect#}</p>
                        <p>
                            <a class="btn btn-box btn-block btn-main-theme" href="{geturl}/{getlang}/account/login/">{#login_title#}</a>
                        </p>
                    </div>
                </div>
            </div>
        {/block}
    </article>
    {/block}

    {block name="article:after"}{/block}
</main>
{/block}