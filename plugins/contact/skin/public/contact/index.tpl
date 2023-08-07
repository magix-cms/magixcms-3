{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{#seo_title_contact#}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{#seo_desc_contact#}]}{/block}
{block name='body:id'}contact{/block}
{block name="webType"}ContactPage{/block}
{block name="styleSheet" nocache}
    {$css_files = ["contact","form"]}
{/block}
{block name="slider"}{/block}

{block name='article'}
    <article class="container">
        {block name='article:content'}
            <h1 itemprop="name">{if $page.name_page}{$page.name_page}{else}{#contact_root_h1#}{/if}</h1>
            <div class="row">
                <section id="form" class="col-12 col-md-8 col-lg-7" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
                    {if !empty($page.content_page)}
                        {$page.content_page}
                    {else}
                        <h2>{#pn_contact_forms#|ucfirst}</h2>
                        <p>{#pn_questions#|ucfirst}</p>
                        <p>{#pn_fill_form#|ucfirst}</p>
                    {/if}
                    {include file="contact/form/contact.tpl"}
                </section>
                <aside id="aside" class="col-12 col-md-4 col-lg-5">
                    {include file="contact/block/map.tpl"}
                </aside>
            </div>
        {/block}
    </article>
{/block}

{block name="scripts"}
    {$jquery = true}
    {$js_files = [
        'group' => ['form'],
        'normal' => [],
        'defer' => ["/skin/{$theme}/js/{if $setting.mode === 'dev'}src/{/if}form{if $setting.mode !== 'dev'}.min{/if}.js"]
    ]}
    {if {$lang} !== "en"}{$js_files['defer'][] = "/libjs/vendor/localization/messages_{$lang}.js"}{/if}
    {*
    {$js_files = [
    'group' => ['vanilla_form'],
    'normal' => [],
    'defer' => [
    "/skin/{$theme}/js/{if $setting.mode === 'dev'}src/{/if}notifier{if $setting.mode !== 'dev'}.min{/if}.js",
    "/skin/{$theme}/js/{if $setting.mode === 'dev'}src/{/if}niceforms{if $setting.mode !== 'dev'}.min{/if}.js",
    "/skin/{$theme}/js/{if $setting.mode === 'dev'}src/{/if}vanilla_form{if $setting.mode !== 'dev'}.min{/if}.js"]
    ]}
    {if $lang !== "en"}{$basejs['defer'][] = "/libjs/vendor/validate_loc/messages_{$lang}.js"}{/if}
    *}
{/block}