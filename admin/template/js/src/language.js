var language = (function ($, undefined) {
    /**
     * Set input data for iso country
     */
    function setInputData(){
        $('select#name_lang').on('change',function() {
            var $currentOption = $(this).find('option:selected').data('iso');
            $('#iso_lang').val($currentOption);
        });
    }

    return {
        runEdit: function (){
            setInputData();
        }
    }
})(jQuery);