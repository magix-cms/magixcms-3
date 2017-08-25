var category = (function ($, undefined) {
    return {
        run: function(controller){
            $.each($( ".ui-sortable" ), function() {
                $( this ).sortable({
                    items: "> tr",
                    cursor: "move",
                    axis: "y",
                    update: function(){
                        var serial = $( this ).sortable('serialize');
                        //console.log(serial);
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
                $( this ).disableSelection();
            });
        },
        runAdd: function(){
            if($('#parent_id').val() != ''){
                var id = $('#parent_id').val();
                var cus = $('#filter-pages').find('li[data-value="'+id+'"]');
                //console.log(cus);
                if(!cus.length) {
                    $('#parent').bootstrapSelect('clear');
                } else {
                    var cu = $(cus[0]);
                    $('#parent').bootstrapSelect('select',cu);
                }
            }
            $('#parent_id').on('focusout',function(){
                var id = $(this).val();
                if(id != '') {
                    var cus = $('#filter-pages').find('li[data-value="'+id+'"]');
                    //console.log(cus);
                    if(!cus.length) {
                        $('#parent').bootstrapSelect('clear');
                    } else {
                        var cu = $(cus[0]);
                        $('#parent').bootstrapSelect('select',cu);
                    }
                }
            });
        }
    }
})(jQuery);