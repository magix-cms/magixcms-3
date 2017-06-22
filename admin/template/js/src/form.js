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

    // *** Set default format for date input
    $('.date-input').formatter({
        'pattern': '{{99}}/{{99}}/{{9999}}',
        'persistent': false
    });

    // *** Set default format for date input
    $('.time-input').formatter({
        'pattern': '{{99}}:{{99}}',
        'persistent': false
    });
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
                initModalActions();
            }
        }
        // --- Rules form classic edit form but with replace from extend data
        else if($(f).hasClass('edit_form_extend')) {
            options.success = function (d) {
                $.jmRequest.initbox(d.notify,{ display:true });
                $.each(d.extend[0], function(i,item) {
                    if($('#lang-'+i).length != 0){
                        $('#lang-'+i+' #public_url'+i).val(item);
                    }
                });
            }
        }
        // --- Rules for add form in a modal
        else if($(f).hasClass('add_modal_form')) {
            options.success = function (d) {
                initAlert(d.notify,4000);
                $('#add_modal').modal('hide');
                if(d.statut && d.result) {
                    //controller = controller.substr(1,(controller.indexOf('.')-1));
                    var table = '#table-'+controller;
                    var nbr = $(table).find('tbody').find('tr').length;
                    if(!nbr) {
                        $(table).removeClass('hide').next('.no-entry').addClass('hide');
                    }
                    $(table).find('tbody').prepend(d.result);
                    initModalActions();
                }
            }
        }
        // --- Rules for add form that add the new record into the associated table
        else if($(f).hasClass('add_to_list')) {
            options.resetForm = true;
            options.success = function (d) {
                sub = $(f).data('sub') == '' ? false : $(f).data('sub');
                initAlert(d.notify,4000,sub);
                if(d.statut && d.result) {
                    var table = $(f).next().find('table');
                    $(table).children('tbody').prepend(d.result).find('a.targetblank').off().on('click',function(){
                        window.open($(this).attr('href'));
                        return false;
                    });
                }
                initValidation(controller,'.edit_in_list');
                initModalActions();
            }
        }
        // --- Rules for edit form that edit a record into a table list
        else if($(f).hasClass('edit_in_list')) {
            options.success = function (data) {
                $.jmRequest.initbox(data.notify, { display: false });
                if(data.statut) {
                    $('[type="submit"]', f).hide();
                    $('.text-success', f).removeClass('hide');

                    window.setTimeout(function () {
                        $('.text-success', f).addClass('hide');
                        $('[type="submit"]', f).show();
                    }, 3000);
                }
            }
        }
        // --- Rules for delete form, will remove the deleted rows form the record list based on their id
        else if($(f).hasClass('delete_form')) {
            options.resetForm = true;
            //controller = sub?sub:controller.substr(1,(controller.indexOf('.')-1));
            controller = sub?sub:controller;
            options.success = function (d) {
                $('#delete_modal').modal('hide');
                //$.jmRequest.notifier.cssClass = '.mc-message-'+controller;
                $.jmRequest.notifier = {
                    cssClass : '.mc-message-'+controller
                };
                initAlert(d.notify,4000);
                if(d.statut && d.result) {
                    var ids = d.result.id.split(',');
                    var nbr = 0;
                    for(var i = 0;i < ids.length; i++) {
                        $('#'+controller+'_' + ids[i]).next('.collapse').remove();
                        $('#'+controller+'_' + ids[i]).remove();
                        nbr = $('#table-'+controller).find('tbody').find('tr').length;
                    }
                    if(!nbr) {
                        $('#table-'+controller).addClass('hide').next('.no-entry').removeClass('hide');
                    }
                    $('.nbr-'+controller).text(nbr);
                    initModalActions();
                }
            }
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

    /**
     * Configure the delete modal by filling the id input and set the destination of the form
     * Then initialise the validation of the delete form
     *
     * @param {string} modal - id of the delete modal.
     * @param {int|string} id - id(s) to be deleted.
     * @param {string} controller - The name of the script to be called by te form.
     * @param {string} sub - The name of the sub-controller used by the script.
     */
    function delete_data(modal, id, controller, sub) {
        $(modal+' input[type="hidden"]').val(id);
        $(modal).modal('show');
        //var url = $('#delete_form').attr('action');
        //$(modal).find('form').attr('action','&action=delete'+(sub?'&tabs='+sub:''));

        initValidation(controller,'#delete_form',sub);
    }

    /**
     * Initialise all modals
     * ---------------------
     *
     * Initialise all delete buttons and retrieves all the data needed for the delete action
     */
    function initModalActions() {
        var modals = $('.modal');

        if(modals.length) {
            modals.modal({show: false});

            $('.modal_action').each(function(){
                $(this).off().on('click',function(e){
                    e.preventDefault();
                    var modal = $(this).data('target'),
                        controller = $(this).data('controller'),
                        sub = $(this).data('sub') ? $(this).data('sub') : false,
                        id = false;

                    if($(this).hasClass('action_on_record')) {
                        id = $(this).data('id') ? $(this).data('id') : false;
                    } else {
                        var selected = $('#table-'+(sub?sub:controller)).find('input[type="checkbox"]:checked');
                        if(selected.length) {
                            var ids = $.map(selected, function (v){ return $(v).val(); });
                            id = ids.join();
                        }
                    }

                    if(modal && id && controller) {
                        delete_data(modal, id, controller, sub);
                    } else {
                        $('#error_modal').modal('show');
                    }
                });
            });
        }
    }

    /**
     * Call the collapse show function on element
     * depending on if it's had been hide previously or not
     * @param box
     */
    function showBox(box) {
        if(!$(box).data('fstt')) {
            $(box).on('hidden.bs.collapse', function() {
                $(this).off('hidden.bs.collapse');
                $(this).collapse('show').data({opened: false, closed: true}).on('hidden.bs.collapse', function() {
                    $(this).data({opened: false, closed: true});
                });
            });
        }
        else {
            $(box).collapse('show');
        }
    }

    /**
     * @param content
     * @param contc
     * @param $boxes
     */
    function displayContent(content,contc,$boxes) {
        if (content != null && content.length > 0) {
            // *** Adding content to the dedicated container(s)

            if(contc.indexOf('|') === -1 && !Array.isArray(content)) {
                var targ = $(contc),
                    $def = targ.children('.default');
                targ.empty();
                if($def != null && $def != undefined) {
                    var dflt = $def.clone();
                    targ.append(dflt);
                }
                targ.append(content);
            }
            else {
                contc = contc.split('|');
                for(var c = 0; c < contc.length; c++) {
                    var targ = $(contc[c]),
                        $def = targ.children('.default');
                    targ.empty();
                    if($def != null && $def != undefined) {
                        var dflt = $def.clone();
                        targ.append(dflt);
                    }
                    if(Array.isArray(content))
                        targ.append(content[c]);
                    else
                        targ.append(content);
                }
            }

            //initOptionalFields(controller);

            // *** Displaying boxe(s)
            if($boxes.indexOf('|') === -1) {
                showBox($boxes);
            }
            else {
                $boxes = $boxes.split("|");
                for (var b = 0; b < $boxes.length; b++) {
                    showBox($boxes[b]);
                }
            }
        }
    }

    /**
     * Initialise the handlers of optional fields
     */
    function initOptionalFields(controller) {
        $('.additional-fields').collapse({toggle: false}).data({fstt: true, opened: false, closed: true})
            .on('shown.bs.collapse', function() {
                $(this).data({opened: true, closed: false});
            })
            .on('hidden.bs.collapse', function() {
                $(this).data({opened: false, closed: true});
            }).each(function(){
                if($(this).hasClass('in')) {
                    $(this).data({fstt: true, opened: true, closed: false})
                }
        });

        $('.has-optional-fields').each(function(){
            var select = $(this);
            var $slct = select.find(':selected');
            var smf = $(this).find('.optional-field');
            var rboxes = [];

            smf.each(function(){
                if($(this).data('target') && rboxes.indexOf($(this).data('target')) === -1)
                    rboxes = rboxes.concat($(this).data('target').split("|"));
            });

            $(this).off('change');
            $(this).on('change',function(){
                var change = $slct != select.find(':selected');
                $slct = select.find(':selected');

                if($slct.length && change) {
                    for(var n = 0; n < rboxes.length; n++) {
                        if($(rboxes[n]).data('closed')) {
                            $(rboxes[n]).data('fstt',true);
                        } else {
                            $(rboxes[n]).collapse('hide');
                            $(rboxes[n]).data('fstt',false);
                        }
                    }

                    if($slct.hasClass('optional-field')) {
                        var $boxes = $slct.data('target'); // Get boxes to display
                        var getc = $slct.data('get'); // get content to retrieve

                        // *** Retrieving content(s)
                        if(getc != undefined && getc != null) {
                            getc = getc.split('|');

                            var contc = $slct.data('appendto'), // get container which receive content
                                id = $slct.data('id'), // get id to specify content
                                content = [],
                                requests = [];

                            for(var c = 0; c < getc.length; c++){
                                requests.push(getContent(controller,getc[c],id,content));
                            }

                            $.when.apply($, requests).done(function(){
                                displayContent(content,contc,$boxes);
                            });

                            function getContent(controller,type,id,content) {
                                var dfd = $.Deferred();

                                $.jmRequest({
                                    handler: "ajax",
                                    url: controller+'&action=get&content='+type+'&id='+id,
                                    method: 'get',
                                    success: function(d){
                                        content.push(d);
                                        dfd.resolve();
                                    }
                                });

                                return dfd.promise();
                            }
                        }
                        else {
                            // *** Displaying boxe(s)
                            if($boxes.indexOf('|') === -1) {
                                showBox($boxes);
                            }
                            else {
                                $boxes = $boxes.split("|");
                                for (var b = 0; b < $boxes.length; b++) {
                                    showBox($boxes[b]);
                                }
                            }
                        }
                    }
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
            // --- Launch modal initialisations
            initModalActions();
            // --- Launch optional fields handler initialisation
            initOptionalFields(controller);
        },
        initModals: function () {
            // --- Launch modal initialisations
            initModalActions();
        }
    }
})(jQuery);