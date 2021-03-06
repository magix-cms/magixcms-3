/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license     Dual licensed under the MIT or GPL Version 3 licenses.
 * @version     1.0
 * @author      Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
'use strict';
window.addEventListener('DOMContentLoaded', function () {
    // *** Auto-position of the affix header
    let tar = window.innerHeight/2;

    function affixHead() {
        let pos = window.pageYOffset,
            header = document.getElementById('header'),
            btn = document.querySelectorAll('.toTop'),
            atTop = header.classList.contains('at-top');

        if (pos > tar && atTop) {
            header.classList.remove('at-top');
            btn.forEach( function(i) { i.classList.remove('at-top'); });
        } else if(pos < tar && !atTop){
            header.classList.add('at-top');
            btn.forEach( function(i) { i.classList.add('at-top'); });
        }
    }

    window.addEventListener('scroll',affixHead);
    window.addEventListener('resize',affixHead);
    affixHead();
});