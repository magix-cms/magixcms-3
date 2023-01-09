/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
'use strict';
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}

function scrollTo(pos = {x: 0,y: 0},scrollDuration) {
    var cosParameter = window.scrollY / 2,
        scrollCount = 0,
        oldTimestamp = window.performance.now();

    function step (newTimestamp) {
        var tsDiff = newTimestamp - oldTimestamp;
        if (tsDiff > 100) tsDiff = 30;
        scrollCount += Math.PI / (scrollDuration / tsDiff);
        if (scrollCount >= Math.PI) {
            window.scrollTo(pos.x, pos.y);
            cancelAnimationFrame(totop);
            return;
        }
        window.scrollTo(pos.x, Math.round(cosParameter + cosParameter * Math.cos(scrollCount)));
        oldTimestamp = newTimestamp;
        window.requestAnimationFrame(step);
    }

    let totop = window.requestAnimationFrame(step);
}

function scrollToTop(scrollDuration) {
    //scrollTo({x: 0,y: 0},scrollDuration);
    window.scroll({
        left: 0,
        top: 0,
        behavior: 'smooth'
    });
}
/*function scrollToTop(scrollDuration) {
    var cosParameter = window.scrollY / 2,
        scrollCount = 0,
        oldTimestamp = window.performance.now();

    function step (newTimestamp) {
        var tsDiff = newTimestamp - oldTimestamp;
        if (tsDiff > 100) tsDiff = 30;
        scrollCount += Math.PI / (scrollDuration / tsDiff);
        if (scrollCount >= Math.PI) {
            window.scrollTo(0, 0);
            cancelAnimationFrame(totop);
            return;
        }
        window.scrollTo(0, Math.round(cosParameter + cosParameter * Math.cos(scrollCount)));
        oldTimestamp = newTimestamp;
        window.requestAnimationFrame(step);
    }

    let totop = window.requestAnimationFrame(step);
}*/

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

function getAbsoluteValue(v) {
    return parseFloat(v);
}

function getCSSProperties(e,p) {
    let computed = window.getComputedStyle(e,p);
    let elem = {
        margin: {
            top: getAbsoluteValue(computed.getPropertyValue('margin-top')),
            left: getAbsoluteValue(computed.getPropertyValue('margin-left')),
            right: getAbsoluteValue(computed.getPropertyValue('margin-right')),
            bottom: getAbsoluteValue(computed.getPropertyValue('margin-bottom'))
        },
        padding: {
            top: getAbsoluteValue(computed.getPropertyValue('padding-top')),
            left: getAbsoluteValue(computed.getPropertyValue('padding-left')),
            right: getAbsoluteValue(computed.getPropertyValue('padding-right')),
            bottom: getAbsoluteValue(computed.getPropertyValue('padding-bottom'))
        },
        height: getAbsoluteValue(computed.getPropertyValue('height')),
        width: getAbsoluteValue(computed.getPropertyValue('width')),
        innerHeight: 0,
        innerWidth: 0,
    };
    elem.innerHeight = elem.height - elem.padding.top - elem.padding.bottom;
    elem.innerWidth = elem.width - elem.padding.left - elem.padding.right;
    return elem;
}

const Cookie = {
    checkCookie: function(name) {
        let nameEQ = name+'=';
        let ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }

            if (c.indexOf(nameEQ) === 0) { return c.substring(nameEQ.length, c.length); }
        }
        return null;
    },
    createCookie: function(name, value, expires, path) {
        let date = new Date();
        date.setTime(date.getTime() + (365*24*60*60*1000));
        value = value  === undefined ? 'on' : value;
        expires = expires === undefined ? date.toGMTString() : expires;
        path = path === undefined ?'/' : path;
        document.cookie = name + '=' + value + '; expires=' + expires + '; path=' + path;
    }
};