var country = (function ($, undefined) {
    /**
     * Set input data for iso country
     */
    function setInputData(){
        $('select#name_country').on('change',function() {
            var $currentOption = $(this).find('option:selected').data('iso');
            $('#iso_country').val($currentOption);
        });
    }

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
        },
        runEdit: function (){
            setInputData();
        }
    }
})(jQuery);