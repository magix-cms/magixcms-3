var setting = (function ($, undefined) {
    return {
        run: function () {
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
        }
    }
})(jQuery);