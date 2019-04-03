<form id="contact-form" class="validate_form nice-form" method="post" action="{$url}/{$lang}/contact/">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <input id="firstname" type="text" name="msg[firstname]" placeholder="{#ph_contact_firstname#|ucfirst}" class="form-control required" required/>
                <label for="firstname">{#pn_contact_firstname#|ucfirst}*&nbsp;:</label>
            </div>
        </div>
        <div class="col-12 col-sm-6">
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
    {if $contact.address_enabled}
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <input id="address" type="text" name="msg[address]" placeholder="{#ph_address#|ucfirst}" value="" class="form-control{if $contact.address_required} required{/if}" {if $contact.address_required}required{/if}/>
                    <label for="address">{#pn_contact_address#|ucfirst}{if $contact.address_required}*{/if}&nbsp;:</label>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <input id="postcode" type="text" name="msg[postcode]" placeholder="{#ph_postcode#|ucfirst}" value="" class="form-control{if $contact.address_required} required{/if}" {if $contact.address_required}required{/if}/>
                    <label for="postcode">{#pn_contact_postcode#|ucfirst}{if $contact.address_required}*{/if}&nbsp;:</label>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-group">
                    <input id="city" type="text" name="msg[city]" placeholder="{#ph_city#|ucfirst}" value="" class="form-control{if $contact.address_required} required{/if}" {if $contact.address_required}required{/if}/>
                    <label for="city">{#pn_contact_city#|ucfirst}{if $contact.address_required}*{/if}&nbsp;:</label>
                </div>
            </div>
        </div>
    {/if}
    <div class="form-group">
        <input id="title" type="text" name="msg[title]" placeholder="{if $smarty.post.moreinfo}{$smarty.post.moreinfo}{else}{#ph_contact_programme#|ucfirst}{/if}"  value="{$smarty.post.moreinfo}" class="form-control required" required/>
        <label for="title">{#pn_contact_programme#|ucfirst}*&nbsp;:</label>
    </div>

    <div class="form-group">
        <textarea id="msg_content" name="msg[content]" rows="5" class="form-control required" required></textarea>
        <label for="msg_content">{#pn_contact_message#|ucfirst}*&nbsp;:</label>
    </div>
    <small class="text-center help-block">{#contact_fiels_resquest#|ucfirst}</small>
    <div class="mc-message"></div>
    <p id="btn-contact">
        <input type="hidden" name="msg[moreinfo]" value="" />
        <button type="submit" class="btn btn-box btn-invert btn-main-theme">{#pn_contact_send#|ucfirst}</button>
    </p>
</form>