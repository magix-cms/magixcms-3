var pages = (function ($, undefined) {
    return {
        run: function(controller){
            $( ".ui-sortable" ).sortable({
                items: "> tr",
                //placeholder: "alert alert-warning",
                //forcePlaceholderSize: true,
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
            $( ".ui-sortable" ).disableSelection();
        }
    }
})(jQuery);