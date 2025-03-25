/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license     Dual licensed under the MIT or GPL Version 3 licenses.
 * @version     1.0
 * @author      Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
'use strict';
function getPosition(element) {
    var xPosition = 0;
    var yPosition = 0;

    while(element) {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }
    return { x: xPosition, y: yPosition };
}
window.addEventListener('DOMContentLoaded', function () {
    // *** Auto-position of the affix header
    let header = document.getElementById('header'),
        footbar = document.getElementById('footbar'),
        //tar = getPosition(header).y,
        tar = window.innerHeight/2;

    /*function init() {
        if(document.getElementsByTagName('body')[0].id === 'home') {
            let headerH = window.getComputedStyle(header).getPropertyValue('--header-h').slice(0,-2),
                rem = window.getComputedStyle(document.documentElement).getPropertyValue('font-size').slice(0,-2);
            tar = document.documentElement.clientHeight - headerH - (10 * rem);
        }
    }*/

    function affixHead() {
        let pos = window.scrollY,
            header = document.getElementById('header'),
            atTop = header.classList.contains('at-top');

        if (pos > tar && atTop) {
            header.classList.remove('at-top');
            footbar.classList.remove('at-top');
        } else if(pos <= tar && !atTop){
            header.classList.add('at-top');
            footbar.classList.add('at-top');
        }
    }

    //window.addEventListener('resize',init);
    window.addEventListener('scroll',affixHead);
    //init();
    affixHead();
});