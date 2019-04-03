{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{#seo_title_contact#}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{#seo_desc_contact#}]}{/block}
{block name='body:id'}contact{/block}
{block name="webType"}ContactPage{/block}

{block name="slider"}{/block}

{block name='article'}
    <article class="container">
        {block name='article:content'}
            <h1 itemprop="name">{#contact_root_h1#}</h1>
            <div class="row">
                <section id="form" class="col-12 col-md-8 col-lg-7" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
                    <h2>{#pn_contact_forms#|ucfirst}</h2>
                    <p>{#pn_questions#|ucfirst}</p>
                    <p>{#pn_fill_form#|ucfirst}</p>
                    {include file="contact/form/contact.tpl"}
                </section>
                <aside id="aside" class="col-12 col-md-4 col-lg-5">
                    {include file="contact/block/map.tpl"}
                </aside>
            </div>
        {/block}
    </article>
{/block}

{block name="foot"}
    {capture name="formVendors"}/min/?g=form{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.formVendors|concat_url:'js'}{else}{$smarty.capture.formVendors}{/if}"></script>
    {capture name="globalForm"}/min/?f=skin/{$theme}/js/form.min.js{if {$lang} !== "en"},libjs/vendor/localization/messages_{$lang}.js{/if}{/capture}
    <script src="{if $setting.concat.value}{$smarty.capture.globalForm|concat_url:'js'}{else}{$smarty.capture.globalForm}{/if}" async defer></script>
{/block}