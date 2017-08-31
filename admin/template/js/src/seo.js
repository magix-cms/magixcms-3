var seo = (function ($, undefined) {
    return {
        run: function(){
            $('a.keyword').jmInsertCaret({
                debug: false
            });
        }
    }
})(jQuery);