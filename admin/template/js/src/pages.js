var pages = (function ($, undefined) {
    return {
        run: function(){
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('.dropdown-menu li.active').removeClass('active');
                $(this).parent('li').addClass('active');
                $('.dropdown .lang').text($(this).text());
                $('[data-toggle="toggle"]').each(function(){
                    $(this).bootstrapToggle('destroy');
                }).each(function(){
                    $(this).bootstrapToggle();
                });
            });
            //Unlock input text
            $('.unlocked').on('click',function(event){
                event.preventDefault();
                var self = $(this);
                var lock = $('span.fa-lock',this);
                var unlock = $('span.fa-unlock',this);
                if (lock.length != 0) {
                    self.parent().prev().removeAttr("readonly");
                    self.parent().prev().removeAttr("disabled");
                    lock.removeClass('fa-lock').addClass('fa-unlock');
                } else {
                    self.parent().prev().attr("readonly","readonly");
                    self.parent().prev().attr("disabled","disabled");
                    unlock.removeClass('fa-unlock').addClass('fa-lock');
                }
            });
        }
    }
})(jQuery);