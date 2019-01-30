/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license     Dual licensed under the MIT or GPL Version 3 licenses.
 * @version     1.0
 * @author      Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */

/*function getPosition(element) {
    var xPosition = 0;
    var yPosition = 0;

    while(element) {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }
    return { x: xPosition, y: yPosition };
}*/

+function ($) {
    'use strict';

    $(window).on('load', function () {
        // *** Auto-position of the affix header
        //let tar = document.documentElement.clientHeight * (1/3);
        //let hH = $('#header').height();
        //tar = (hH - 92);
        //let tar = (hH - 150);
        let tar = window.innerHeight/2;

        //let toptar = document.documentElement.clientHeight * (1/3);
        function affixHead() {
            let pos = window.pageYOffset,
                atTop = $('#header').hasClass('at-top');

            if (pos > tar && atTop) {
                $('#header').removeClass('at-top');
                $('body > .toTop').addClass('affix');
            } else if(pos < tar && !atTop){
                $('#header').addClass('at-top');
                $('body > .toTop').removeClass('affix');
            }
        }
        $(window).scroll(affixHead);
        $(window).resize(affixHead);
        affixHead();
    });
}(jQuery);