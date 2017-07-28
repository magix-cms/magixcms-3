var about = (function ($, undefined) {
    return {
        run: function(baseadmin){
            /*$('#info_language_form').validate({
                onsubmit: true,
                event: 'submit',
                submitHandler: function(form) {
                    $.jmRequest({
                        handler: "submit",
                        url: '/'+baseadmin+'/index.php?controller=about&action=edit',
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
                }else{
                    $('.open_hours input', line).prop('disabled', true);
                    $('.noon_hours input', line).prop('disabled', true);
                    $('.noon_time', line).bootstrapToggle('disable');
                }
            });

            $('.noon_time').change(function(){
                var day = $(this).data('day'),
                    line = $('#opening_'+day);

                if( $(this).prop("checked") == true) {
                    $('.noon_hours input', line).prop('disabled', false);
                }else{
                    $('.noon_hours input', line).prop('disabled', true);
                }
            });
        }
    }
})(jQuery);