{extends file="amp/layout.tpl"}
{block name="stylesheet"}{fetch file="skin/{$theme}/amp/css/contact.min.css"}{/block}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>{#seo_title_contact#}]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>{#seo_desc_contact#}]}{/block}
{block name='body:id'}contact{/block}
{block name="webType"}ContactPage{/block}
{block name="amp-script"}
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    {*<script async custom-theme="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>*}
{/block}

{block name='article'}
    <article class="container">
        {block name='article:content'}
            <h1 itemprop="name">{#contact_root_h1#}</h1>
            <section id="form" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
                <h2>{#pn_contact_forms#|ucfirst}</h2>
                <p>{#pn_questions#|ucfirst}</p>
                <p>{#pn_fill_form#|ucfirst}</p>
                <p class="help-block">{#contact_fiels_resquest#|ucfirst}</p>
                <form id="contact-form" method="post" action-xhr="{$url|replace:'http://':'//'}{$smarty.server.REQUEST_URI}" target="_top" custom-validation-reporting="show-all-on-submit">
                    <div class="row">
                        <div class="col-12 col-xs-6">
                            <div class="form-group">
                                <input id="firstname" type="text" name="msg[firstname]" placeholder="{#ph_contact_firstname#|ucfirst}" class="form-control required" pattern="{literal}[0-9a-zA-ZÁÀÂÄÃáàâäãÓÒÔÖÕóòôöõÍÌÎÏíìîïÉÈÊËéèêëÚÙÛÜúùûüÆæŒœåÇßçñÑØøðýÿþÐŠŽšžŸÝ _\-']+{/literal}" required />
                                <label for="firstname">{#pn_contact_firstname#|ucfirst}*&nbsp;:</label>
                                {include file="amp/section/form/value-missing.tpl" msg="{#firstname_missing#}" input="firstname"}
                                {include file="amp/section/form/pattern-mismatch.tpl" msg="{#firstname_mismatch#}" input="firstname"}
                            </div>
                        </div>
                        <div class="col-12 col-xs-6">
                            <div class="form-group">
                                <input id="lastname" type="text" name="msg[lastname]" placeholder="{#ph_contact_lastname#|ucfirst}" class="form-control required" pattern="{literal}[0-9a-zA-ZÁÀÂÄÃáàâäãÓÒÔÖÕóòôöõÍÌÎÏíìîïÉÈÊËéèêëÚÙÛÜúùûüÆæŒœåÇßçñÑØøðýÿþÐŠŽšžŸÝ _\-']+{/literal}" required />
                                <label for="lastname">{#pn_contact_lastname#|ucfirst}*&nbsp;:</label>
                                {include file="amp/section/form/value-missing.tpl" msg="{#lastname_missing#}" input="lastname"}
                                {include file="amp/section/form/pattern-mismatch.tpl" msg="{#lastname_mismatch#}" input="lastname"}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input id="email" type="email" name="msg[email]" placeholder="{#ph_contact_mail#|ucfirst}" class="form-control required" required />
                        <label for="email">{#pn_contact_mail#|ucfirst}*&nbsp;:</label>
                        {include file="amp/section/form/value-missing.tpl" msg="{#email_missing#}" input="email"}
                        {include file="amp/section/form/pattern-mismatch.tpl" msg="{#email_mismatch#}" input="email"}
                    </div>
                    <div class="form-group">
                        <input id="phone" type="tel" name="msg[phone]" placeholder="{#ph_contact_phone#|ucfirst}" class="form-control phone" pattern="{literal}^((?=[0-9\+ \(\)-]{9,20})(\+)?\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3})${/literal}" maxlength="20" />
                        <label for="tel">{#pn_contact_phone#|ucfirst}&nbsp;:</label>
                        {include file="amp/section/form/pattern-mismatch.tpl" msg="{#phone_mismatch#}" input="phone"}
                    </div>
                    {if $address_enabled}
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <input id="address" type="text" name="msg[address]" placeholder="{#ph_address#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" pattern="{literal}[0-9a-zA-ZÁÀÂÄÃáàâäãÓÒÔÖÕóòôöõÍÌÎÏíìîïÉÈÊËéèêëÚÙÛÜúùûüÆæŒœåÇßçñÑØøðýÿþÐŠŽšžŸÝ _\-']+{/literal}" {if $address_required}required {/if}/>
                                    <label for="address">{#pn_contact_address#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                    {if $address_required}{include file="amp/section/form/value-missing.tpl" msg="{#address_missing#}" input="address"}{/if}
                                    {include file="amp/section/form/pattern-mismatch.tpl" msg="{#address_mismatch#}" input="address"}
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <input id="postcode" type="text" name="msg[postcode]" placeholder="{#ph_postcode#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" pattern="[a-zA-Z0-9_\- ]+" maxlength="12" {if $address_required}required {/if}/>
                                    <label for="postcode">{#pn_contact_postcode#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                    {if $address_required}{include file="amp/section/form/value-missing.tpl" msg="{#postcode_missing#}" input="postcode"}{/if}
                                    {include file="amp/section/form/pattern-mismatch.tpl" msg="{#postcode_mismatch#}" input="postcode"}
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <input id="city" type="text" name="msg[city]" placeholder="{#ph_city#|ucfirst}" value="" class="form-control{if $address_required} required{/if}" pattern="{literal}[0-9a-zA-ZÁÀÂÄÃáàâäãÓÒÔÖÕóòôöõÍÌÎÏíìîïÉÈÊËéèêëÚÙÛÜúùûüÆæŒœåÇßçñÑØøðýÿþÐŠŽšžŸÝ _\-']+{/literal}" {if $address_required}required {/if}/>
                                    <label for="city">{#pn_contact_city#|ucfirst}{if $address_required}*{/if}&nbsp;:</label>
                                    {if $address_required}{include file="amp/section/form/value-missing.tpl" msg="{#city_missing#}" input="city"}{/if}
                                    {include file="amp/section/form/pattern-mismatch.tpl" msg="{#city_mismatch#}" input="city"}
                                </div>
                            </div>
                        </div>
                    {/if}
                    <div class="form-group">
                        <input id="title" type="text" name="msg[title]" placeholder="{#ph_contact_programme#|ucfirst}"  value="{$title}" class="form-control required{if $title} user-valid valid{/if}" required />
                        <label for="title">{#pn_contact_programme#|ucfirst}*&nbsp;:</label>
                        {include file="amp/section/form/value-missing.tpl" msg="{#title_missing#}" input="title"}
                    </div>

                    <div class="form-group">
                        <textarea id="content" name="msg[content]" rows="5" class="form-control required" required ></textarea>
                        <label for="content">{#pn_contact_message#|ucfirst}*&nbsp;:</label>
                        {include file="amp/section/form/value-missing.tpl" msg="{#content_missing#}" input="content"}
                    </div>
                    <p id="btn-contact">
                        <input type="hidden" name="msg[moreinfo]" value="" />
                        <button type="submit" class="btn btn-box btn-invert btn-main-theme"><i class="material-icons">send</i> {#pn_contact_send#|ucfirst}</button>
                    </p>
                    <div class="alert alert-success" submit-success>
                        <p>{#message_send_success#|ucfirst}</p>
                    </div>
                    <div class="alert alert-warning" submit-error>
                        <p>{#message_send_error#|ucfirst}</p>
                    </div>
                </form>
            </section>
            <section id="aside">
                {include file="amp/contact/block/map.tpl"}
            </section>
        {/block}
    </article>
{/block}