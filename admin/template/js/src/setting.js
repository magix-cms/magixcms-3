var setting = (function ($, undefined) {
    function UpdateSkin(controller,btnData){
        $(document).on("click",'.skin-select', function(event){
            event.preventDefault();
            var self = $(this);
            var skin = self.data("skin");
            if(skin != null){
                $.jmRequest({
                    handler: "ajax",
                    url: controller+'&action=edit',
                    method: 'POST',
                    data: { 'setting[theme]': skin,'type': 'theme' },
                    resetForm:false,
                    beforeSend:function(){},
                    success:function(data) {
                        /*if(data.statut != false){
                         window.setTimeout(function() { $(".alert-success").alert('close'); }, 4000);
                         }else{
                         window.setTimeout(function() { $(".alert-warning").alert('close'); }, 4000);
                         }*/
                        $.jmRequest.initbox(data.notify, {
                            display: false
                        });
                        $('.skin-select').text(btnData[0]);
                        $('.skin-select.btn-success').removeClass('btn-success').addClass('btn-default');
                        if (self.hasClass('btn-default')) {
                            self.removeClass('btn-default').addClass('btn-success').text(btnData[1]);
                        }

                    }
                });
                return false;
            }
        });
    }
    return {
        run: function (controller, btnData) {
            var $options = $('.optional-fields');
            $('.csspicker').colorpicker();
            $options.each(function(){
                var $box = $($(this).data('target'));

                if( $(this).attr('type') == 'checkbox' ) {
                    if ( $(this).prop('checked') ) {
                        $action = 'show';
                    } else {
                        $action = 'hide';
                    }
                    $box.collapse($action);
                }

                var $slct = $(this).find(':selected');
                if ( $slct.data('open') ) {
                    $action = 'show';
                    $box.find('input').rules('add', {required: true});
                } else {
                    $action = 'hide';
                    $box.find('input').rules('remove');
                }
                $box.collapse($action);

                $(this).on('change',function(){
                    if( $(this).attr('type') == 'checkbox' ) {
                        if ( $(this).prop('checked') ) {
                            $action = 'show';
                            //$('#passwd_customer').rules('add', {required: true});
                            //$('#repeat_passwd').rules('add', {required: true, equalTo: '#passwd_customer'});
                        } else {
                            $action = 'hide';
                            //$('#passwd_customer').rules('remove');
                            //$('#repeat_passwd').rules('remove');
                        }
                    } else if ( $(this).is('select') ) {
                        var $slct = $(this).find(':selected');
                        if ( $slct.data('open') ) {
                            $action = 'show';
                            $box.find('input').rules('add', {required: true});
                        } else {
                            $action = 'hide';
                            $box.find('input').rules('remove');
                        }
                    }

                    $box.collapse($action);
                });
            });
            UpdateSkin(controller,btnData);

            $('#ssl').change(function(){
                let checked = $(this).prop('checked');
                $('#service_worker').prop('disabled',!checked);
                if(checked) $('#service_worker').parent().removeAttr('disabled');
                else $('#service_worker').parent().attr('disabled','disabled');
                /*$('#amp').prop('disabled',!checked);
                if(checked) $('#amp').parent().removeAttr('disabled');
                else $('#amp').parent().attr('disabled','disabled');*/
            });

            $('.advanced-popover').each(function(){
                let $this = $(this);
                $(this).popover('destroy').popover({
                    html: true,
                    placement: 'bottom',
                    content: function() {
                        let type = $this.parent().attr('for');
                        return $('#popover-content-' + type).html();
                    }
                });
            });
        }
    }
})(jQuery);