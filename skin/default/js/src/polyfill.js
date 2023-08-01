/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
'use strict';
/*if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}*/

Number.prototype.between = (a, b, inclusive) => {
    var min = Math.min.apply(Math, [a, b]),
        max = Math.max.apply(Math, [a, b]);
    return inclusive ? this >= min && this <= max : this > min && this < max;
};

//Returns true if it is a DOM element
function isElement(o){
    return (
        typeof HTMLElement === "object" ? o instanceof HTMLElement : //DOM2
            o && typeof o === "object" && true && o.nodeType === 1 && typeof o.nodeName==="string"
    );
}

/**
 * @param {object} obj
 * @returns {boolean}
 */
function isEmpty(obj) {
    for(let prop in obj) {
        if(obj.hasOwnProperty(prop)) {
            return false;
        }
    }

    return JSON.stringify(obj) === JSON.stringify({});
}

/**
 * Select elements matching the selector through querySelectorAll in the context, loop through them and apply the callback function
 * @param {string} selector - selector to match.
 * @param {function(Element|Node,Number)|function(Element|Node,Number): void} callback - callback to apply on each element
 * @param {Element|Document} [context=document] - context to apply the querySelector
 */
function forEach(selector, callback, context = document) {
    context.querySelectorAll(selector).forEach((e,i) => {
        callback(e,i);
    })
}

/**
 * Scroll to Top
 */
function scrollToTop() {
    window.scroll({
        left: 0,
        top: 0,
        behavior: 'smooth'
    });
}

/**
 * Return absolute position of the element on the page in an object format {x,y}
 * @param {Element} element
 * @returns {{x: number, y: number}}
 */
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

/**
 * @param {string} v
 * @returns {number}
 */
function getAbsoluteValue(v) {
    return parseFloat(v);
}

/**
 *
 * @param {Element} e
 * @param {string|null} p
 * @returns {{padding: {top: number, left: number, bottom: number, right: number}, innerWidth: number, margin: {top: number, left: number, bottom: number, right: number}, innerHeight: number, width: number, height: number}}
 */
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
    checkCookie: (name) => {
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
    createCookie: (name, value, expires, path, domain) => {
        let date = new Date();
        date.setTime(date.getTime() + (365*24*60*60*1000));
        value = value  === undefined ? 'on' : value;
        expires = expires === undefined ? date.toGMTString() : expires;
        path = path === undefined ?'/' : path;
        document.cookie = name + '=' + value + '; expires=' + expires + '; path=' + path + (domain !== undefined ? '; domain=' + domain : '');
    },
    deleteAllCookies: () => {
        let ca = document.cookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            let eqPos = c.indexOf("=");
            let name = eqPos > -1 ? c.substring(0, eqPos) : c;
            Cookie.createCookie(name,'','Thu, 01 Jan 1970 00:00:00 GMT','/',window.location.hostname.substring(3));
        }
        window.localStorage.clear();
        sessionStorage.clear();
    }
};

function viewportUnitValue() {
    // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
    let vh = window.innerHeight * 0.01;
    let vw = document.body.clientWidth * 0.01;
    // Then we set the value in the --vh custom property to the root of the document
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    document.documentElement.style.setProperty('--vw', `${vw}px`);
}

/**
 * Returns rem value in px
 * @param {number} rem
 * @returns {number}
 */
function rem2px(rem) {
    return rem * parseFloat(getComputedStyle(document.documentElement).fontSize);
}