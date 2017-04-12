var access = (function ($, undefined) {
    return {
        runEdit: function(){
            $('#selectAll').on('click',function() {  //on click
                if(this.checked) { // check select status
                    $('.checkbox-access').each(function() { //loop through each checkbox
                        this.checked = true;  //select all checkboxes with class "checkbox-access"
                    });
                }else{
                    $('.checkbox-access').each(function() { //loop through each checkbox
                        this.checked = false; //deselect all checkboxes with class "checkbox-access"
                    });
                }
            });
        }
    }
})(jQuery);