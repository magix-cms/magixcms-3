var tableForm = (function ($, undefined) {
    'use strict';
    /**
     * Initializes the multi-select checkboxes
     */
    function initCheckboxSelect() {
        $('.check-all').off().on('change',function(){
            let table = $(this).data('table');
            let chb = $('#table-'+table+' input[type="checkbox"]:enabled');
            console.log(table);
            console.log(chb);
            chb.prop('checked',$(this).prop('checked'));
        });

        $('.update-checkbox').off().on('click',function(e){
            e.preventDefault();
            let table = $(this).data('table');
            let chb = $('#table-'+table+' input[type="checkbox"]:enabled'),
                checked = ($(this).val() === 'check-all');

            $('#'+table+' .check-all').prop('checked',checked);
            chb.prop('checked',checked);
            return false;
        });
    }

    /**
     * Public method of tableForm object
     */
    return {
        // Public Functions
        run: function (controller) {
            // Initialization of the multi-select checkboxes
            initCheckboxSelect();

            $.each($( ".ui-sortable" ), function() {
                $( this ).sortable({
                    items: "> tr",
                    cursor: "move",
                    axis: "y",
                    update: function(){
                        let serial = $( this ).sortable('serialize');
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
                $( this ).disableSelection();
            });
        }
    };
})(jQuery);