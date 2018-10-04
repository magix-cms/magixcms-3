var theme = (function ($, undefined) {
    function UpdateSkin(controller,btnData){
        $(document).on("click",'.skin-select', function(event){
            event.preventDefault();
            var self = $(this);
            var skin = self.data("skin");
            if(skin != null){
                $.jmRequest({
                    handler: "ajax",
                    url: controller+'&action=edit',
                    method: 'POST',
                    data: { 'theme': skin,'type': 'theme' },
                    resetForm:false,
                    beforeSend:function(){},
                    success:function(data) {
                        $.jmRequest.initbox(data.notify, {
                            display: false
                        });
                        $('.skin-select').text(btnData[0]).removeClass('btn-success').addClass('btn-default');
                        if (self.hasClass('btn-default')) {
                            self.removeClass('btn-default').addClass('btn-success').text(btnData[1]);
                        }
                    }
                });
                return false;
            }
        });
    }

    function initSortable() {
        $( ".sortable" ).sortable({
            items: "> li",
            handle: "header .fa-arrows-alt",
            cursor: "move",
            placeholder: "list-group-item list-group-item-default",
            update: function(){
                var serial = $( ".sortable" ).sortable('serialize', { key: "order[]" });
                $.jmRequest({
                    handler: "ajax",
                    url: '/admin/index.php?controller=theme&action=order',
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
        }).disableSelection();
    }

    return {
        run: function (controller, btnData) {
            UpdateSkin(controller,btnData);
            initSortable();

            $('[data-toggle="popover"]').popover();
            $('[data-toggle="popover"]').click(function(e){
                e.preventDefault(); return false;
            });

            $('#share-twitter').on('change',function(){
                $('#twitter-id').prop('disabled',!$(this).prop('checked'));
            });
        }
    }
})(jQuery);