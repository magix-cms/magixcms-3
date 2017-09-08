/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien, 
 * http://www.magix-cms.com, magix-cms.com http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.0
 * @author Gérits Aurélien <aurelien@magix-cms.com>
 * JS theme default
 *
 */
$(document).ready(function(){
    // *** Set default values for forms validation
	jQuery.validator.addClassRules("phone", {
		pattern: '/^((?=[0-9\+\-\ \(\)]{9,20})(\+)?\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3}(-| )?\d{1,3})$/'
	});

	$.validator.setDefaults({
        debug: false,
		highlight: function(element, errorClass, validClass) {
			if($(element).parent().is("div") || $(element).parent().is("p")) {
				if($(element).parent().hasClass('input-group')) {
					$(element).parent().parent().addClass("has-error has-feedback");
				} else {
					if(!$(element).parent().hasClass('has-error'))
						$(element).parent().append('<span class="fa fa-warning form-control-feedback" aria-hidden="true"></span>');
					$(element).parent().addClass("has-error has-feedback");
				}
			}
			else if($(element).is('[type="radio"]')) {
				$(element).parent().parent().addClass("has-error").parent().addClass("has-error");
			}
		},
		unhighlight: function(element, errorClass, validClass) {
			if($(element).parent().is("div") || $(element).parent().is("p")) {
				if($(element).parent().hasClass('input-group')) {
					$(element).parent().parent().removeClass("has-error has-feedback");
				} else {
					if($(element).parent().hasClass('has-error'))
						$(element).next('.fa').remove();
					$(element).parent().removeClass("has-error has-feedback");
				}
			}
			else if($(element).is('[type="radio"]')) {
				$(element).parent().parent().removeClass("has-error").parent().removeClass("has-error");
			}
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			if ( element.is(":radio") ){
				element.parent().parent().parent().append(error);
			}else if ( element.is(":checkbox") ){
				error.insertAfter(element);
			}else if ( element.is("select")){
				error.insertAfter(element);
			}else if ( element.is(".checkMail") ){
				error.insertAfter(element.next());
			}else if ( element.is("#cryptpass") ){
				error.insertAfter(element.next());
				$("<br />").insertBefore(error);
			}else{
				if(element.next().is(":button") || element.next().is(":file")){
					error.insertAfter(element);
					$("<br />").insertBefore(error);
				}else if ( element.next().is(":submit") ){
					error.insertAfter(element.next());
					$("<br />").insertBefore(error);
				}else{
					if($(element).parent().hasClass('input-group')) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}

				}
			}
		},
		errorClass: "help-block error",
		errorElement: "span",
		validClass: "success",
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.remove();
		}
    });
});

var globalForm = (function ($, undefined) {
	/**
	 * Replace the submit button by a loader icon.
	 * @param {string} f - id of the form.
	 * @param {boolean} [closeForm=true] - hide the form.
	 */
	function displayLoader(f,closeForm) {
		$('input[type="submit"], button[type="submit"]').hide();
		closeForm = typeof closeForm !== 'undefined' ? closeForm : false;
		var loader = $(document.createElement("div")).addClass("loader")
			.append(
				$(document.createElement("i")).addClass("fa fa-spinner fa-pulse fa-2x fa-fw"),
				$(document.createElement("span")).append("Chargement en cours...").addClass("sr-only")
			);
		if(closeForm) $(f).collapse();
		$('.mc-message').before(loader);
	}

	/**
	 * Remove the loader icon.
	 * @param {string} f - id of the form.
	 * @param {boolean} [closeForm=true] - hide the form.
	 */
	function removeLoader(f,closeForm) {
		closeForm = typeof closeForm !== 'undefined' ? closeForm : false;
		if(closeForm) $(f).collapse('hide');
		$('.loader').remove();
		$('input[type="submit"], button[type="submit"]').show();
	}

	/**
	 * Initialise the display of notice message
	 * @param {html} m - message to display.
	 * @param {int|boolean} [timeout=false] - Time before hiding the message.
	 * @param {string|boolean} [sub=false] - Sub-controller name to select the container for the message.
	 */
	function initAlert(m,timeout) {
		timeout = typeof timeout !== 'undefined' ? timeout : false;
		$.nicenotify.initbox(m,{ display:true });
		if(timeout) window.setTimeout(function () { $('.mc-message .alert').removeClass('in').remove(); }, timeout);
	}

	/**
	 * Assign the correct success handler depending of the validation class attached to the form
	 * @param {string} f - id of the form.
	 */
	function successHandler(f) {
		// --- Default options of the ajax request
		var options = {
			ntype: "submit",
			uri: $(f).attr('action'),
			typesend: 'post',
			idforms: $(f),
			resetform: true,
			beforeParams: function () {
				displayLoader(f);
			},
			successParams: function (d) {
				removeLoader(f);
				if(d.debug != undefined && d.debug != '') {
					initAlert(d.debug);
				}
				else if(d.notify != undefined && d.notify != '') {
					initAlert(d.notify,4000);
				}
				else if(d != undefined && d != '') {
					initAlert(d,4000);
				}
			}
		};

		// --- Initialise the ajax request
		$.nicenotify(options);
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
		});
	}

	return {
		/**
		 * Public functions
		 */
		run: function () {
			$.gForms = globalForm;
			// --- Launch forms validators initialisation
			initValidation();
		}
	}
})(jQuery);