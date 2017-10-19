{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{template}/amp/css/contact.min.css"}{/block}
{block name='body:id'}contact{/block}
{block name="webType"}ContactPage{/block}
{block name="amp-script"}
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>
{/block}

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
                    <form id="contact-form" method="post" action-xhr="{geturl|replace:'http://':'//'}{$smarty.server.REQUEST_URI}" target="_top" custom-validation-reporting="show-all-on-submit">
                        <div class="row">
                            <div class="col-ph-12 col-xs-6">
                                <div class="form-group">
                                    <input id="firstname" type="text" name="msg[firstname]" placeholder="{#ph_contact_firstname#|ucfirst}" class="form-control required" pattern="[a-zA-Z0-9_\-]+" required />
                                    <label for="firstname">{#pn_contact_firstname#|ucfirst}*&nbsp;:</label>
                                    {include file="amp/section/form/value-missing.tpl" msg="Please enter your first name"}
                                    {include file="amp/section/form/pattern-mismatch.tpl" msg="Please use valid characters"}
                                </div>
                            </div>
                            <div class="col-ph-12 col-xs-6">
                                <div class="form-group">
                                    <input id="lastname" type="text" name="msg[lastname]" placeholder="{#ph_contact_lastname#|ucfirst}" class="form-control required" pattern="[a-zA-Z0-9_\-]+" required />
                                    <label for="lastname">{#pn_contact_lastname#|ucfirst}*&nbsp;:</label>
                                    {include file="amp/section/form/value-missing.tpl" msg="Please enter your last name"}
                                    {include file="amp/section/form/pattern-mismatch.tpl" msg="Please use valid characters"}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input id="email" type="email" name="msg[email]" placeholder="{#ph_contact_mail#|ucfirst}" class="form-control required" required />
                            <label for="email">{#pn_contact_mail#|ucfirst}*&nbsp;:</label>
                            {include file="amp/section/form/value-missing.tpl" msg="Please enter your email"}
                            {include file="amp/section/form/pattern-mismatch.tpl" msg="Please enter a valid email"}
                        </div>
                        <div class="form-group">
                            <input id="phone" type="tel" name="msg[phone]" placeholder="{#ph_contact_phone#|ucfirst}" class="form-control phone" pattern="{literal}^((?=[0-9\+ \(\)-]{9,20})(\+)?\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3})${/literal}" maxlength="20" />
                            <label for="tel">{#pn_contact_phone#|ucfirst}&nbsp;:</label>
                            {include file="amp/section/form/pattern-mismatch.tpl" msg="Please enter a valid phone number"}
                        </div>
                        {if $address_enabled}
                            <div class="row">
                                <div class="col-ph-12 col-md-6">
                                    <div class="form-group">
                                        <input id="address" type="text" name="msg[address]" placeholder="{#ph_address#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" pattern="[a-zA-Z0-9_\-\s]+" {if $address_required}required {/if}/>
                                        <label for="address">{#pn_contact_address#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                        {if $address_required}{include file="amp/section/form/value-missing.tpl" msg="Please enter your address"}{/if}
                                        {include file="amp/section/form/pattern-mismatch.tpl" msg="Please enter a valid address : Street Number"}
                                    </div>
                                </div>
                                <div class="col-ph-6 col-md-3">
                                    <div class="form-group">
                                        <input id="postcode" type="text" name="msg[postcode]" placeholder="{#ph_postcode#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" pattern="[a-zA-Z0-9_\-\s]+" maxlength="12" {if $address_required}required {/if}/>
                                        <label for="postcode">{#pn_contact_postcode#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                        {if $address_required}{include file="amp/section/form/value-missing.tpl" msg="Please enter your postcode"}{/if}
                                        {include file="amp/section/form/pattern-mismatch.tpl" msg="Please enter a valid postcode | Max length 12 characters"}
                                    </div>
                                </div>
                                <div class="col-ph-6 col-md-3">
                                    <div class="form-group">
                                        <input id="city" type="text" name="msg[city]" placeholder="{#ph_city#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" pattern="[a-zA-Z0-9_\-\s]+" {if $address_required}required {/if}/>
                                        <label for="city">{#pn_contact_city#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                        {if $address_required}{include file="amp/section/form/value-missing.tpl" msg="Please enter your city"}{/if}
                                        {include file="amp/section/form/pattern-mismatch.tpl" msg="Please use valid characters"}
                                    </div>
                                </div>
                            </div>
                        {/if}
                        <div class="form-group">
                            <input id="title" type="text" name="msg[title]" placeholder="{if $smarty.post.moreinfo}{$smarty.post.moreinfo}{else}{#ph_contact_programme#|ucfirst}{/if}"  value="{$smarty.post.moreinfo}" class="form-control required" required />
                            <label for="title">{#pn_contact_programme#|ucfirst}*&nbsp;:</label>
                        </div>

                        <div class="form-group">
                            <textarea id="content" name="msg[content]" rows="5" class="form-control required" required ></textarea>
                            <label for="content">{#pn_contact_message#|ucfirst}*&nbsp;:</label>
                        </div>
                        <div class="mc-message"></div>
                        <p id="btn-contact">
                            <input type="hidden" name="moreinfo" value="" />
                            <button type="submit" class="btn btn-box btn-invert btn-main-theme">{#pn_contact_send#|ucfirst}</button>
                        </p>
                        <div submit-success>
                            <template type="amp-mustache">
                                {literal}Success!{/literal}
                            </template>
                        </div>
                        <div submit-error>
                            <template type="amp-mustache">
                                {literal}Oops!{/literal}
                            </template>
                        </div>
                    </form>
                </section>{*
                <section id="sidebar" class="col-ph-12 col-md-4 col-lg-5">
                    {include file="contact/block/map.tpl"}
                </section>*}
            </div>
        {/block}
    </article>
{/block}