var tableForm = (function ($, undefined) {
    /**
     * Initializes the multi-select checkboxes
     */
    function initCheckboxSelect() {
        $('.check-all').on('change',function(){
            var table = $(this).data('table'),
                chb = $('#'+table+' input[type="checkbox"]:enabled');
            if($(this).prop('checked')) {
                chb.prop('checked',true);
            } else {
                chb.prop('checked',false);
            }
        });

        $('.update-checkbox').on('click',function(e){
            e.preventDefault();
            var table = $(this).data('table'),
                chb = $('#'+table+' input[type="checkbox"]:enabled');

            if($(this).val() == 'check-all') {
                $('#'+table+' .check-all').prop('checked',true);
                chb.prop('checked',true);
            } else {
                $('#'+table+' .check-all').prop('checked',false);
                chb.prop('checked',false);
            }
            return false;
        });
    }

    /**
     * Public method of tableForm object
     */
    return {
        // Public Functions
        run: function () {
            // Initialization of the multi-select checkboxes
            initCheckboxSelect();
        }
    }
})(jQuery);