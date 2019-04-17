/**
 * MAGIX CMS
 * @copyright MAGIX CMS Copyright (c) 2018,
 * http://www.magix-cms.com, magix-cms.com http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 3.0
 * @author Salvatore Di Salvo <www.disalvo-infographiste.be>
 * @author Gérits Aurélien <aurelien@magix-cms.com>
 */
const niceForms = (function ($) {
    'use strict';

    function isEmpty(elem) {
        const val = elem.val();
        return ((typeof val === 'string' && val.length === 0) || (typeof val === 'object' && val == null));
    }

    function updateParent(elem) {
        $('[for="' + elem.attr('id') + '"]').toggleClass('is_empty', isEmpty(elem));
    }

    function reset() {
        $('form').each(function(){
            let nicefields = $(this).find('input:not(.not-nice),textarea:not(.not-nice),select:not(.not-nice)');
            nicefields.each(function(){updateParent($(this));});
        });
    }

    function init() {
        $('form').each(function(){
            let input = $(this).find('input:not(.not-nice)');
            let niceElem = $(this).find('textarea:not(.not-nice),select:not(.not-nice)');

            input.each(function(){
                let self = $(this);
                if(self.attr('type') !== 'hidden') {
                    updateParent(self);
                    self.on('change',function(){updateParent(self)});
				}
            });
            niceElem.each(function(){
                let self = $(this);
                updateParent(self);
                self.on('change',function(){updateParent(self)});
            });
        });
    }

    return {
        /**
         * Public functions
         */
        init: function () { init(); },
        reset: function () { reset(); }
    };
})(jQuery);

const globalForm = (function ($) {
    'use strict';
    /**
     * Replace the submit button by a loader icon.
     * @param {string} f - id of the form.
     * @param {boolean} [closeForm=true] - hide the form.
     */
    function displayLoader(f,closeForm) {
        $('input[type="submit"], button[type="submit"]').hide();
        closeForm = typeof closeForm !== 'undefined' ? closeForm : false;
        const loader = $(document.createElement("div")).addClass("loader")
            .append(
                $(document.createElement("i")).addClass("fa fa-spinner fa-pulse fa-fw"),
                $(document.createElement("span")).append("Chargement en cours...").addClass("sr-only")
            );
        if(closeForm) $(f).collapse();
        $('.mc-message').addClass('text-center').append(loader);
    }

    /**
     * Remove the loader icon.
     * @param {string} f - id of the form.
     * @param {boolean} [closeForm=true] - hide the form.
     */
    function removeLoader(f,closeForm) {
        closeForm = typeof closeForm !== 'undefined' ? closeForm : false;
        if(closeForm) $(f).collapse('hide');
        $('.mc-message').removeClass('text-center');
        $('.loader').remove();
        $('input[type="submit"], button[type="submit"]').show();
    }

    /**
     * Initialise the display of notice message
     * @param {html} m - message to display.
     * @param {int|boolean} [timeout=false] - Time before hiding the message.
     * @param {string|boolean} [sub=false] - Sub-controller name to select the container for the message.
     * @param {string|boolean} [modal=false] - Modal id.
     */
    function initAlert(m,timeout,sub,modal) {
        sub = typeof sub !== 'undefined' ? sub : false;
        timeout = typeof timeout !== 'undefined' ? timeout : false;
        modal = typeof modal !== 'undefined' ? modal : false;
        if(sub) $.jmRequest.notifier = { box:"", cssClass : '.mc-message-'+sub };
        $.jmRequest.initbox(m,{ display:true });
        if(timeout) window.setTimeout(function () {
            $('.mc-message .alert').removeClass('in').remove();
            if(modal) { $(modal).modal('hide'); }
        }, timeout);
    }

    /**
     * Assign the correct success handler depending of the validation class attached to the form
     * @param {string} f - id of the form.
     */
    function successHandler(f) {
        // --- Default options of the ajax request
        let options = {
            handler: "submit",
            url: $(f).attr('action'),
            method: 'post',
            form: $(f),
            resetForm: true,
            beforeSend: function () {
                displayLoader(f);
            },
            success: function (d) {
                removeLoader(f);
                niceForms.reset();
                let modal = $(f).data('modal');
                //if(modal) { $(modal).modal('hide'); }
                let sub = $(f).data('sub');
                if(d.debug !== undefined && d.debug !== '') {
                    initAlert(d.debug,false,sub,modal);
                }
                else if(d.notify !== undefined && d.notify !== '') {
                    initAlert(d.notify,4000,sub,modal);
                }
                else if(d !== undefined && d !== '') {
                    initAlert(d,4000,sub,modal);
                }
            }
        };

        // --- Rules form classic add form
        if($(f).hasClass('edit_form')) {
            options.resetForm = false;
        }
        else if($(f).hasClass('refresh_form')) {
            options.resetForm = false;
            options.success = function (d) {
                removeLoader(f);
                let modal = $(f).data('modal');
                if(modal) { $(modal).modal('hide'); }
                let sub = $(f).data('sub');
                if(d.debug !== undefined && d.debug !== '') {
                    initAlert(d.debug,false,sub);
                }
                else if(d.notify !== undefined && d.notify !== '') {
                    initAlert(d.notify,4000,sub);
                }
                else if(d !== undefined && d !== '') {
                    initAlert(d,4000,sub);
                }
                window.reload();
            };
        }
        else if($(f).hasClass('button_feedback')) {
            options.beforeSend = function(){
                $(f).find('button[type="submit"]').replaceWith('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
            };
            options.success = function () {
                $(f).hide().next('.success').removeClass('hide');
            };
        }
        else if($(f).hasClass('static_feedback')) {
            options.success = function (d) {
                removeLoader(f);
                let modal = $(f).data('modal');
                if(modal) { $(modal).modal('hide'); }
                let sub = $(f).data('sub');
                if(d.debug !== undefined && d.debug !== '') {
                    initAlert(d.debug,false,sub);
                }
                else if(d.notify !== undefined && d.notify !== '') {
                    initAlert(d.notify,false,sub);
                }
                else if(d !== undefined && d !== '') {
                    initAlert(d,false,sub);
                }
            };
        }

        // --- Initialise the ajax request
        $.jmRequest(options);
    }

    /**
     * Initialise the rules of validation for the form(s) matching the selector passed throught the form parameter
     */
    function initValidation() {
        // --- Global validation rules
        $('.validate_form').each(function(){
            $(this).removeData();
            $(this).off();
            $(this).validate({
                ignore: [],
                onsubmit: true,
                event: 'submit',
                submitHandler: function(f,e) {
                    e.preventDefault();
                    successHandler(f);
                    return false;
                }
            });

            var onloadCallback = function() {
                console.log("grecaptcha is ready!");
                let recaptcha = $(this).find(".hiddenRecaptcha");
                //console.log($(recaptcha[0]));
                if ( recaptcha.length && typeof grecaptcha !== "undefined") {
                    $(recaptcha[0]).rules('add',{
                        required: function() {
                            if(grecaptcha.getResponse() == '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    });
                }
            };
        });

        $('.validate').each(function(){
            $(this).removeData();
            $(this).off();
            $(this).validate({
                ignore: []
            });
        });
    }

    /**
     * Initialise the rules of validation for the Google Recaptcha
     */
    function initRecaptcha() {
        // --- Global validation rules
        $('.validate_form').each(function(){
            let recaptcha = $(this).find(".hiddenRecaptcha");
            if ( recaptcha.length && typeof grecaptcha !== "undefined") {
                $(recaptcha[0]).rules('add',{
                    required: function() {
                        if(grecaptcha.getResponse() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
            }
        });
    }

    return {
        /**
         * Public functions
         */
        run: function () {
            $.gForms = globalForm;
            $.validator.setDefaults({
                debug: false,
                highlight: function(element, errorClass, validClass) {
                    let parent = $(element).parent();
                    if(parent.is("div,p")) {
                        if(parent.hasClass('input-group')) {
                            parent.parent().addClass(errorClass+" has-feedback");
                        } else {
                            if(!parent.hasClass(errorClass)) parent.append('<span class="fas fa-exclamation-triangle form-control-feedback" aria-hidden="true"></span>');
                            parent.addClass(errorClass+" has-feedback");
                        }
                    }
                    else if($(element).is('[type="radio"],[type="checkbox"]')) {
                        parent.parent().addClass("has-error").parent().addClass("has-error");
                    }
                },
                unhighlight: function(element, errorClass, validClass) {
                    let parent = $(element).parent();
                    if(parent.is("div,p")) {
                        if(parent.hasClass('input-group')) {
                            parent.parent().removeClass(errorClass+" has-feedback");
                        } else {
                            if(parent.hasClass(errorClass)) parent.find('.fas').remove();
                            parent.removeClass(errorClass+" has-feedback");
                        }
                    }
                    else if($(element).is('[type="radio"],[type="checkbox"]')) {
                        parent.parent().removeClass("has-error").parent().removeClass("has-error");
                    }
                },
                // the errorPlacement has to take the table layout into account
                errorPlacement: function(error, element) {
                    error.addClass('help-block error');
                    if ( element.is(":radio") ) {
                        element.parent().parent().parent().append(error);
                    } else if ( element.is(":checkbox,.checkMail")) {
                        error.insertAfter(element.next());
                    } else if ( element.is("#cryptpass,:submit")) {
                        error.insertAfter(element.next());
                        $("<br />").insertBefore(error,null);
                    } else if ( element.next().is(":button,:file") ) {
                        error.insertAfter(element);
                        $("<br />").insertBefore(error,null);
                    } else if ( element.parent().hasClass('input-group') ) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                errorClass: "has-error",
                errorElement: "span",
                validClass: "success",
                // set this class to error-labels to indicate valid fields
                success: function(label) {
                    // set &nbsp; as text for IE
                    label.remove();
                }
            });
            // --- Launch forms validators initialisation
            initValidation();
        },
        onloadRecaptcha: function() {
            //console.log("grecaptcha is ready!");
            initRecaptcha();
        }
    };
})(jQuery);

// $(document).ready(function(){
    // *** Set default values for forms validation
	/*jQuery.validator.addClassRules("phone", {
		pattern: '((?=[0-9\+\-\ \(\)]{9,20})(\+)?\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3})'
	});*/

	niceForms.init();
	globalForm.run();
	var onloadCallback = function () {
	    globalForm.onloadRecaptcha();
    };
// });