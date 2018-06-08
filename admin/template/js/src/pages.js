var pages = (function ($, undefined) {
    'use strict';
    return {
        run: function(controller){
            $( ".ui-sortable" ).sortable({
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
                }
            });
            $( ".ui-sortable" ).disableSelection();
        },
        runAdd : function(){
            if($('#parent_id').val() !== ''){
                var id = $('#parent_id').val();
                var cus = $('#filter-pages').find('li[data-value="'+id+'"]');
                if(!cus.length) {
                    $('#parent').bootstrapSelect('clear');
                } else {
                    var cu = $(cus[0]);
                    $('#parent').bootstrapSelect('select',cu);
                }
            }
            $('#parent_id').on('focusout',function(){
                var id = $(this).val();
                if(id !== '') {
                    var cus = $('#filter-pages').find('li[data-value="'+id+'"]');
                    if(!cus.length) {
                        $('#parent').bootstrapSelect('clear');
                        $('#parent_id').val('');
                    } else {
                        var cu = $(cus[0]);
                        $('#parent').bootstrapSelect('select',cu);
                    }
                }
            });
        }
    };
})(jQuery);