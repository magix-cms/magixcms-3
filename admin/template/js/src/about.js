var about = (function ($, undefined) {
    return {
        run: function(){
            /*$('#info_language_form').validate({
                onsubmit: true,
                event: 'submit',
                submitHandler: function(form) {
                    $.jmRequest({
                        handler: "submit",
                        url: controller+'&action=edit',
                        method: 'post',
                        form: $(form),
                        resetForm: false,
                        successParams:function(d){
                            if(d.result) {
                                window.setTimeout(function() { $(".alert-success").alert('close'); }, 4000);
                                $.jmRequest.notifier = { cssClass : '.mc-message' };
                                $.jmRequest.initbox(d.notify,{
                                    display:true
                                });
                            }
                        }
                    });
                    return false;
                }
            });*/
            /*$( ".ui-sortable" ).sortable({
                items: "> tr",
                cursor: "move",
                axis: "y",
                update: function(){
                    var serial = $( ".ui-sortable" ).sortable('serialize');
                    $.jmRequest({
                        handler: "ajax",
                        url: controller+'&action=order',
                        method: 'POST',
                        data : serial,
                        success:function(e){
                            $.jmRequest.initbox(e,{
                                    display: false
                                }
                            );
                        }
                    });
                    //return false;
                }
            });
            $( ".ui-sortable" ).disableSelection();*/

            $('[data-toggle="popover"]').popover();
            $('[data-toggle="popover"]').click(function(e){
                e.preventDefault(); return false;
            });

            $('#info_opening_form').collapse();

            $('#enable_op').change(function(){
                $('#enable_op_form').submit();

                if($('#enable_op').prop('checked')) {
                    $('#info_opening_form').collapse('show');
                }else{
                    $('#info_opening_form').collapse('hide');
                }
            });

            $('.open_day').change(function(){
                var day = $(this).data('day'),
                    line = $('#opening_'+day);

                if( $(this).prop("checked") == true) {
                    $('.open_hours input', line).prop('disabled', false);
                    $('.noon_time', line).bootstrapToggle('enable');

                    if($('.noon_time', line).prop('checked')) {
                        $('.noon_hours input', line).prop('disabled', false);
                    }

                    $('.close_txt', line).addClass('hide');
                    $('.schedules', line).removeClass('hide');
                    $('.close_txt input', line).prop('disabled', true);

                }else{
                    $('.open_hours input', line).prop('disabled', true);
                    $('.noon_hours input', line).prop('disabled', true);
                    $('.noon_time', line).bootstrapToggle('disable');

                    $('.schedules', line).addClass('hide');
                    $('.close_txt', line).removeClass('hide');
                    $('.close_txt input', line).prop('disabled', false);
                }
            });

            $('.noon_time').change(function(){
                var day = $(this).data('day'),
                    line = $('#opening_'+day);

                if( $(this).prop("checked") == true) {
                    $('.noon_hours input', line).prop('disabled', false);
                    $('.noon_hours', line).removeClass('hide');
                }else{
                    $('.noon_hours input', line).prop('disabled', true);
                    $('.noon_hours', line).addClass('hide');
                }
            });
        }
    }
})(jQuery);