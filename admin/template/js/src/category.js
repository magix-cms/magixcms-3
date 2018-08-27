var category = (function ($, undefined) {
    'use strict';
    function updSelect($id) {
        if($id !== '') {
            let cus = $('#filter-pages').find('li[data-value="'+$id+'"]');
            if(!cus.length) {
                $('#parent').bootstrapSelect('clear');
                $('#parent_id').val('');
            } else {
                let cu = $(cus[0]);
                $('#parent').bootstrapSelect('select',cu);
            }
        }
    }

    return {
        runAdd: function(){
            let parent = $('#parent_id');
            if(parent.val() !== ''){
                let id = parent.val();
                updSelect(id);
            }
            parent.on('focusout',function(){
                let id = $(this).val();
                updSelect(id);
            });
        }
    };
})(jQuery);