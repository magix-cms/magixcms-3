/**
 * @version    1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
$(document).ready(function(){
    // *** Set default values for forms validation
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
    $.jmRequest.notifier = {
        cssClass : '.mc-message'
    };
});

var globalForm = (function ($, undefined) {
    /**
     * Redirection function.
     * @param {string} loc - url where to redirect.
     * @param {int} [timeout=2800] - Time before redirection.
     */
    function redirect(loc,timeout) {
        timeout = typeof timeout !== 'undefined' ? timeout : 2800;
        setTimeout(function(){
            window.location.href = loc;
        },timeout);
    }

    /**
     * Replace the submit button by a loader icon.
     * @param {string} f - id of the form.
     * @param {boolean} [closeForm=true] - hide the form.
     */
    function displayLoader(f,closeForm) {
        closeForm = typeof closeForm !== 'undefined' ? closeForm : true;
        var loader = $(document.createElement("div")).addClass("loader pull-right")
            .append(
                $(document.createElement("i")).addClass("fa fa-spinner fa-pulse fa-2x fa-fw"),
                $(document.createElement("span")).append("Opération en cours...").addClass("sr-only")
            );
        if(closeForm) $(f).collapse();
        $('button[type="submit"]').parent().empty().append(loader);
    }

    /**
     * Remove the loader icon.
     * @param {string} f - id of the form.
     * @param {boolean} [closeForm=true] - hide the form.
     */
    function removeLoader(f,closeForm) {
        closeForm = typeof closeForm !== 'undefined' ? closeForm : true;
        if(closeForm) $(f).collapse('hide');
        $('.loader').parent().empty();
    }

    /**
     * Initialise the display of notice message
     * @param {html} m - message to display.
     * @param {int|boolean} [timeout=false] - Time before hiding the message.
     * @param {string|boolean} [sub=false] - Sub-controller name to select the container for the message.
     */
    function initAlert(m,timeout,sub) {
        sub = typeof sub !== 'undefined' ? sub : false;
        timeout = typeof timeout !== 'undefined' ? timeout : false;
        if(sub) $.jmRequest.notifier = { cssClass : '.mc-message-'+sub };
        $.jmRequest.initbox(m,{ display:true });
        if(timeout) window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, timeout);
    }

    /**
     * Assign the correct success handler depending of the validation class attached to the form
     * @param {string} f - id of the form.
     * @param {string} controller - The name of the script to be called by te form.
     * @param {string|boolean} sub - The name of the sub-controller used by the script.
     */
    function successHandler(f,controller,sub) {
        // --- Default options of the ajax request
        var options = {
            handler: "submit",
            url: $(f).attr('action'),
            method: 'post',
            form: $(f),
            resetForm: false,
            success: function (d) {
                if(d.debug != undefined && d.debug != '') {
                    initAlert(d.debug);
                }
                else if(d.notify != undefined && d.notify != '') {
                    initAlert(d.notify,4000);
                }
            }
        };

        // --- Rules form classic add form
        if($(f).hasClass('add_form')) {
            options.beforeSend = function(){ displayLoader(f); };
            options.success = function (d) {
                removeLoader(f);
                $.jmRequest.initbox(d.notify,{ display:true });
                redirect(controller);
            };
        }
        else if($(f).hasClass('config_form')) {
            options.beforeSend = function(){ displayLoader(f); };
            options.success = function (d) {
                removeLoader(f);
                $.jmRequest.initbox(d.notify,{ display:false });
                redirect(controller);
            };
        }
        // --- Rules form search form
        else if($(f).hasClass('pwd_form')) {
            options.resetForm = true;
        }

        // --- Initialise the ajax request
        $.jmRequest(options);
    }

    /**
     * Initialise the rules of validation for the form(s) matching the selector passed throught the form parameter
     * @param {string} controller - The name of the script to be called by te form.
     * @param {string} form - id of the form.
     * @param {string} sub - The name of the sub-controller used by the script.
     */
    function initValidation(controller,form,sub) {
        form = typeof form !== 'undefined' ? form : '.validate_form';
        sub = typeof sub !== 'undefined' ? sub : false;

        // --- Global validation rules
        $(form).each(function(){
            $(this).removeData();
            $(this).off();
            $(this).validate({
                ignore: [],
                onsubmit: true,
                event: 'submit',
                submitHandler: function(f,e) {
                    e.preventDefault();
                    successHandler(f,controller,sub);
                    return false;
                }
            });
        });
    }

    return {
        /**
         * Public functions
         * @param {string} controller - The name of the script to be called by te form.
         */
        run: function (controller) {
            $.gForms = globalForm;
            // --- Launch forms validators initialisation
            initValidation(controller);
        }
    };
})(jQuery);