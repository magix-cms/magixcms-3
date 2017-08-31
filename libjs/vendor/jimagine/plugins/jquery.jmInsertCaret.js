/**
 * Example
 * <a href="#" class="keyword" data-keyword="[[EXAMPLE]]" data-target="mytextarea">
     [[EXAMPLE]]
   </a>
 */
;(function ( $, window, document, undefined ) {
    $.fn.jmInsertCaret = function(settings) {
        var options =  {
            debug: false
        };

        if ($.isPlainObject(settings)) {
            var o = $.extend(true, options, settings || {});
        }else{
            console.log("%s: %o","insertCaret settings is not object");
        }

        /**
         * Add cursor text position
         * @param textArea
         * @param cursorPosition
         * @param text
         */
        function addTextAtCursorPosition(textArea, cursorPosition, text) {
            var front = (textArea.value).substring(0, cursorPosition);
            var back = (textArea.value).substring(cursorPosition, textArea.value.length);
            textArea.value = front + text + back;
        }

        /**
         * Update Cursor Position
         * @param cursor
         * @param text
         * @param textArea
         */
        function updateCursorPosition(cursor, text, textArea) {
            var cursorPosition = cursor + text.length;
            textArea.selectionStart = cursorPosition;
            textArea.selectionEnd = cursorPosition;
            textArea.focus();
        }

        /**
         * Ini insert text
         * @param keyword
         */
        function addTextAtCaret(keyword,target) {
            var textArea = document.getElementById(target);
            var cursorPosition = textArea.selectionStart;
            addTextAtCursorPosition(textArea, cursorPosition, keyword);
            updateCursorPosition(cursorPosition, keyword, textArea);
        }

        return this.each(function(i, item){
            $(item).off();
            $(item).on('click',function(e){
                e.preventDefault();
                var selfelem = $(this);
                var keyword = selfelem.data('keyword');
                var target = selfelem.data('target');
                if(options.debug){
                    console.log(selfelem);
                    console.log(keyword);
                    console.log(document.getElementById(target));
                }
                if(keyword.length != '0'){
                    addTextAtCaret(keyword,target);
                }else{
                    console.log("%s: %o","keyword is NULL");
                }
            });
        });
    };
})( jQuery, window, document );