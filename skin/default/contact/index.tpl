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
                <section id="form" class="col-ph-12 col-md-8 col-lg-7" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
                    <h2>{#pn_contact_forms#|ucfirst}</h2>
                    <p>{#pn_questions#|ucfirst}</p>
                    <p>{#pn_fill_form#|ucfirst}</p>
                    <p class="help-block">{#contact_fiels_resquest#|ucfirst}</p>
                    <form id="contact-form" class="validate_form nice-form" method="post" action="{$smarty.server.REQUEST_URI}">
                        <div class="row">
                            <div class="col-ph-12 col-sm-6">
                                <div class="form-group">
                                    <input id="firstname" type="text" name="msg[firstname]" placeholder="{#ph_contact_firstname#|ucfirst}" class="form-control required" required/>
                                    <label for="firstname">{#pn_contact_firstname#|ucfirst}*&nbsp;:</label>
                                </div>
                            </div>
                            <div class="col-ph-12 col-sm-6">
                                <div class="form-group">
                                    <input id="lastname" type="text" name="msg[lastname]" placeholder="{#ph_contact_lastname#|ucfirst}" class="form-control required" required/>
                                    <label for="lastname">{#pn_contact_lastname#|ucfirst}*&nbsp;:</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input id="email" type="email" name="msg[email]" placeholder="{#ph_contact_mail#|ucfirst}" class="form-control required" required/>
                            <label for="email">{#pn_contact_mail#|ucfirst}*&nbsp;:</label>
                        </div>
                        <div class="form-group">
                            <input id="phone" type="tel" name="msg[phone]" placeholder="{#ph_contact_phone#|ucfirst}" class="form-control phone" pattern="{literal}^((?=[0-9\+ \(\)-]{9,20})(\+)?\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3})${/literal}" maxlength="20" />
                            <label for="phone">{#pn_contact_phone#|ucfirst}&nbsp;:</label>
                        </div>
                        {if $address_enabled}
                            <div class="row">
                                <div class="col-ph-12 col-md-6">
                                    <div class="form-group">
                                        <input id="address" type="text" name="msg[address]" placeholder="{#ph_address#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" {if $address_required}required{/if}/>
                                        <label for="address">{#pn_contact_address#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                    </div>
                                </div>
                                <div class="col-ph-6 col-md-3">
                                    <div class="form-group">
                                        <input id="postcode" type="text" name="msg[postcode]" placeholder="{#ph_postcode#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" {if $address_required}required{/if}/>
                                        <label for="postcode">{#pn_contact_postcode#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                    </div>
                                </div>
                                <div class="col-ph-6 col-md-3">
                                    <div class="form-group">
                                        <input id="city" type="text" name="msg[city]" placeholder="{#ph_city#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" {if $address_required}required{/if}/>
                                        <label for="city">{#pn_contact_city#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                    </div>
                                </div>
                            </div>
                        {/if}
                        <div class="form-group">
                            <input id="title" type="text" name="msg[title]" placeholder="{if $smarty.post.moreinfo}{$smarty.post.moreinfo}{else}{#ph_contact_programme#|ucfirst}{/if}"  value="{$smarty.post.moreinfo}" class="form-control required" required/>
                            <label for="title">{#pn_contact_programme#|ucfirst}*&nbsp;:</label>
                        </div>

                        <div class="form-group">
                            <textarea id="content" name="msg[content]" rows="5" class="form-control required" required></textarea>
                            <label for="content">{#pn_contact_message#|ucfirst}*&nbsp;:</label>
                        </div>
                        <div class="mc-message"></div>
                        <p id="btn-contact">
                            <input type="hidden" name="msg[moreinfo]" value="" />
                            <button type="submit" class="btn btn-box btn-invert btn-main-theme">{#pn_contact_send#|ucfirst}</button>
                        </p>
                    </form>
                </section>
                <section id="aside" class="col-ph-12 col-md-4 col-lg-5">
                    {include file="contact/block/map.tpl"}
                </section>
            </div>
        {/block}
    </article>
{/block}

{block name="foot"}
    {script src="/min/?g=form" concat=$concat type="javascript"}
    {script src="/min/?f=skin/{$theme}/js/form.min.js" concat=$concat type="javascript"}
    {if {$lang} !== "en"}
        {script src="/min/?f=libjs/vendor/localization/messages_{$lang}.js" concat=$concat type="javascript"}
    {/if}
    <script type="text/javascript">
		$(function(){
			if (typeof globalForm == "undefined")
			{
				console.log("globalForm is not defined");
			}else{
				globalForm.run();
			}
		});
    </script>
{/block}