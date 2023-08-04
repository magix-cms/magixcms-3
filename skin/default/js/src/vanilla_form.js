/**
 * MAGIX CMS
 * @copyright MAGIX CMS Copyright (c) 2018,
 * http://www.magix-cms.com, magix-cms.com http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 3.0
 * @author Salvatore Di Salvo <www.disalvo-infographiste.be>
 * @author Gérits Aurélien <aurelien@magix-cms.com>
 */
'use strict';

/**
 * Add the classes hidden and hide to the elements matching the selector
 * @param {string} selector - selector to match.
 * @param {Element|Document} [context=document] - context to apply the querySelector
 */
function hide(selector, context = document) {
    forEach(selector,(e) => e.classList.add('hidden','hide') ,context);
}

/**
 * Remove the classes hidden and hide to the elements matching the selector
 * @param {string} selector - selector to match.
 * @param {Element|Document} [context=document] - context to apply the querySelector
 */
function show(selector, context = document) {
    forEach(selector,(e) => e.classList.remove('hidden','hide') ,context);
}

/**
 * @name Global Form
 * @Description Form Validator and success handler
 * @version 3.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @date_created 21-05-2019
 */
class GlobalForm {
    /**
     * constructor
     * @param {object} options
     */
    constructor(options) {
        if(typeof Notifier === "undefined") throw new Error("Notifier is required by GlobalForm to run properly");
        if(typeof SimpleRequest === "undefined") throw new Error("SimpleRequest is required by GlobalForm to run properly");
        this.forms = {
            selector: '.validate_form'
        };
        this.message = {
            selector: '.mc-message'
        };
        if(typeof options === 'object') this.set(options);
        this.notifier = new Notifier();
        this.loader = null;
    }

    /**
     * Override default property values
     * @param {object} options
     */
    set(options) {
        let instance = this;
        for (let key in options) {
            if (options.hasOwnProperty(key)) instance[key] = options[key];
        }
    }

    /**
     * Initialise the display of notice message
     * @param {string|html} m - message to display.
     * @param {int|boolean} [timeout=false] - Time before hiding the message.
     * @param {string|boolean} [sub=false] - Sub-controller name to select the container for the message.
     */
    initAlert(m,timeout,sub) {
        let instance = this;
        sub = typeof sub !== 'undefined' ? sub : false;
        timeout = typeof timeout !== 'undefined' ? timeout : false;
        if(sub) instance.notifier.set({ cssClass : instance.message.selector+'-'+sub });
        instance.notifier.notify(m);
        if(typeof timeout === 'number') window.setTimeout(() => instance.notifier.close(), timeout);
    }

    /**
     * Redirection function.
     * @param {string} loc - url where to redirect.
     * @param {int} [timeout=2800] - Time before redirection.
     * @param {string} action - action parameter.
     * @param {int} id - edit parameter.
     */
    redirect(loc,timeout,action,id) {
        timeout = typeof timeout !== 'undefined' ? timeout : 2800;
        action = typeof action === 'string' ? action : null;
        id = typeof id !== 'undefined' ? id : null;
        setTimeout(() =>{
            window.location.href = loc + (action !== null ? '&action='+action : '') + (Number.isInteger(id) ? '&edit='+id : '');
        },timeout);
    }

    /**
     * Create the html loader to be inserted during the request
     */
    createLoader() {
        let loader = document.createElement("div");
        loader.classList.add('loader');
        let spinner = document.createElement("i");
        spinner.classList.add('fa','fa-spinner','fa-pulse','fa-fw');
        let text = document.createElement("span");
        text.innerText = "Chargement en cours...";
        text.classList.add('sr-only');
        loader.append(spinner,text);
        this.loader = loader;
    }

    /**
     * Replace the submit button by a loader icon.
     * @param {Element} f - id of the form.
     * @param {boolean} [closeForm=true] - hide the form.
     */
    displayLoader(f,closeForm) {
        let instance = this;
        hide('[type="submit"]',f);
        closeForm = typeof closeForm !== 'undefined' ? closeForm : true;
        if(closeForm) f.classList.add('hidden','hide');
        let box = f.querySelector(instance.message.selector);
        if (instance.loader === null) instance.createLoader();
        box.append(instance.loader);
        box.classList.add('text-center');
    }

    /**
     * Remove the loader icon.
     * @param {Element} f - id of the form.
     * @param {boolean} [closeForm=true] - hide the form.
     */
    removeLoader(f,closeForm) {
        let instance = this;
        closeForm = typeof closeForm !== 'undefined' ? closeForm : true;
        if(closeForm) f.classList.remove('hidden','hide');
        let box = f.querySelector(instance.message.selector);
        box.classList.remove('text-center');
        box.innerHTML = '';
        show('[type="submit"]',f);
    }

    /**
     * Assign the correct success handler depending on the validation class attached to the form
     * @param {Element} f - id of the form.
     */
    successHandler(f) {
        let instance = this;

        // --- Default options of the request
        let options = {
            url: f.getAttribute('action'),
            method: 'post',
            form: f,
            resetForm: true,
            beforeSend: () => {
                instance.displayLoader(f);
            },
            success: (response) => {
                instance.removeLoader(f);
                let d = response.data;
                if (typeof d === 'string') {
                    instance.initAlert(d, 4000);
                }
                else if(d.debug !== undefined && d.debug !== '') {
                    instance.initAlert(d.debug);
                }
                else if(d.notify !== undefined && d.notify !== '') {
                    instance.initAlert(d.notify,4000);
                }
            },
            complete: () => {
                if(f.NF instanceof NiceForms) f.NF.reset();
            }
        };

        // --- Initialise the ajax request
        let Request = new SimpleRequest();
        Request.post(options.url, MagicForm.getFormDataFromData({},f), options);
    }

    /**
     * Initialise the rules of validation for the form(s) matching the selector passed throught the form parameter
     */
    initValidation() {
        let instance = this;

        // --- Global validation rules
        forEach(instance.forms.selector,(form) => {
            let validator = new Validator(form,{
                event: 'submit',
                eventHandler: (f,e) => {
                    let caller = f.dataset.caller;
                    if (caller === 'submit') {
                        f.submit();
                    }
                    else {
                        e.preventDefault();
                        instance.successHandler(f);
                        return false;
                    }
                }
            });
            validator.listen();
        });
    }

    /**
     * Initialize the recaptcha feature
     */
    onloadRecaptcha() {
        forEach(this.forms.selector,(form) => {
            forEach('.hiddenRecaptcha',(rc) => {
                if (typeof rc !== "undefined") {
                    $(rc).rules('add',{
                        required: () => {
                            return grecaptcha.getResponse() === '';
                        }
                    });
                    if (form.validator !== undefined) {
                        form.validator.addRule({ presence: true })
                    }
                }
            },form);
        });
    }

    /**
     * Initialize every feature
     */
    initialize() {
        // --- Launch forms validators initialisation
        this.initValidation();
    }
}

const globalForm = new GlobalForm();
var onloadCallback = () => globalForm.onloadRecaptcha();
window.addEventListener('DOMContentLoaded',() => globalForm.initialize());