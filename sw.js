/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */

'use strict';

const VERSION = 3;
const debug = false; // Show log on Service Worker events
// Cache folders
const folders = {
    global : 'static-cache-v' + VERSION, // JSON and various files
    css : 'css-cache-v' + VERSION, // Stylesheets
    scripts : 'scripts-cache-v' + VERSION, // Javascript files
    images : 'imgs-cache-v' + VERSION, // Images
    fonts : 'fonts-cache-v' + VERSION, // Fonts
    pages : 'pages-cache-v' + VERSION // Documents (HTML files)
};
const globalFiles = [
    '/'
]; // Default files to cache

// Default configuration based on the default theme
const defaultTheme = "default";
const defaultThemePath = "skin/"+defaultTheme;
/**
 * Configuration object
 * @property (string) theme, name of the current theme being used
 * @property (string) themePath, path to the current theme
 * @property (array) stylesheetsFiles, list of the stylesheets to be cached
 * @property (array) scriptsFiles, list of the javascript files to be cached
 */
const config = {
    theme: defaultTheme,
    themePath: "skin/"+defaultTheme,
    stylesheetsFiles: [
        '/min/?f='+defaultThemePath+'/css/mobile.min.css',
        '/min/?f='+defaultThemePath+'/css/tablet.min.css',
        '/min/?f='+defaultThemePath+'/css/desktop.min.css'
    ],
    scriptsFiles: [
        '/min/?f='+defaultThemePath+'/js/vendor/modernizr.min.js,' +
        defaultThemePath+'/js/vendor/picturefill.min.js,' +
        defaultThemePath+'/js/vendor/intersection-observer.min.js',
        '/min/?f='+defaultThemePath+'/js/vendor/bootstrap-custom.min.js,' +
        defaultThemePath+'/js/vendor/jquery.detect_swipe.min.js,' +
        defaultThemePath+'/js/vendor/featherlight.min.js,' +
        defaultThemePath+'/js/vendor/featherlight.gallery.min.js,' +
        defaultThemePath+'/js/vendor/owl.carousel.min.js,' +
        defaultThemePath+'/js/vendor/lazysizes.min.js,' +
        defaultThemePath+'/js/affixhead.min.js,' +
        defaultThemePath+'/js/global.min.js'
    ]
};

/**
 * setConfig try to get the name of the current theme being used, then try to get the Service Worker Configuration json file linked
 */
function setConfig(){
    var themeReq = '/service/?get=theme';
    caches.match(themeReq)
        .then((response) => {
            if (response) return response;

            return fetch(themeReq).then(
                (response) => {
                    if (!response || response.status !== 200 || (response.type !== 'basic' && response.type !== 'cors')) return response;
                    var responseToCache = response.clone();

                    caches.open(folders.global)
                        .then((cache) => {
                            cache.put(themeReq, responseToCache);
                        });

                    return response;
                }
            );
        })
        .then((response) => {
            var contentType = response.headers.get("content-type");
            if(contentType && contentType.indexOf("application/json") !== -1) {
                return response.json().then(function(json) {
                    var setupReq = 'skin/'+json.theme+'/swsetup.json';
                    caches.match(setupReq)
                        .then((response) => {
                            if (response) return response;

                            return fetch(setupReq).then(
                                (response) => {
                                    if (!response || response.status !== 200 || (response.type !== 'basic' && response.type !== 'cors')) return response;
                                    var responseToCache = response.clone();

                                    caches.open(folders.global)
                                        .then((cache) => {
                                            cache.put(setupReq, responseToCache);
                                        });

                                    return response;
                                }
                            );
                        })
                        .then((response) => {
                            var contentType = response.headers.get("content-type");
                            if(contentType && contentType.indexOf("application/json") !== -1) {
                                return response.json().then(function(json) {
                                    config.theme = json.theme;
                                    config.themePath = json.themePath;
                                    config.stylesheetsFiles = json.stylesheetsFiles;
                                    config.scriptsFiles = json.scriptsFiles;
                                });
                            }
                            else {
                                console.log("Response received wasn't JSON");
                                return response;
                            }
                        });

                });
            }
            else {
                console.log("Response received wasn't JSON");
                return response;
            }
        });
}

self.addEventListener('install', (evt) => {
    if (debug) console.log('[ServiceWorker] Install');
    setConfig();

    evt.waitUntil(
        caches.open(folders.global).then((cache) => {
            if (debug) console.log('[ServiceWorker] Pre-caching global files');
            return cache.addAll(globalFiles);
        })
    );
    evt.waitUntil(
        caches.open(folders.css).then((cache) => {
            if (debug) console.log('[ServiceWorker] Pre-caching stylesheets');
            return cache.addAll(config.stylesheetsFiles);
        })
    );
    evt.waitUntil(
        caches.open(folders.scripts).then((cache) => {
            if (debug) console.log('[ServiceWorker] Pre-caching scripts');
            return cache.addAll(config.scriptsFiles);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (evt) => {
    if (debug) console.log('[ServiceWorker] Activate');
    setConfig();
    evt.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (Object.values(folders).indexOf(cacheName) === -1) return caches.delete(cacheName);
                })
            );
        })
    );
});

self.addEventListener('fetch', (evt) => {
    let exceptions = ['/admin/','/install/','/webservice/'],
        regex = new RegExp(exceptions.map(function(w){ return '\\b'+w+'\\b' }).join('|'),'g');
    if(!regex.test(evt.request.url)) {
        setConfig();
        let filecache = '';

        switch (evt.request.destination) {
            case 'document':
                filecache = folders.pages;
                break;
            case 'style':
                filecache = folders.css;
                break;
            case 'script':
                filecache = folders.scripts;
                break;
            case 'image':
                filecache = folders.images;
                break;
            case 'font':
                filecache = folders.fonts;
                break;
            default:
                filecache = folders.global;
        }

        if (debug) console.log('[ServiceWorker] Fetch ('+ evt.request.destination +' file)', evt.request.url);

        // If the request is about a document or an image, we try to get one from the server to replace the cache version but display the cache version anyway
        if(evt.request.destination === 'document' || evt.request.destination === 'image') {
            let cache_response = null;
            let serv_response = null;

            evt.respondWith(
                caches.match(evt.request)
                    .then((response) => {
                        // Cache hit - return response
                        if (response) {
                            cache_response = response;
                            //return response;
                        }

                        serv_response = fetch(evt.request).then(
                            (response) => {
                                // Check if we received a valid response
                                if(!response || response.status !== 200 || response.type !== 'basic') {
                                    return response;
                                }

                                if(response !== cache_response) {
                                    // IMPORTANT: Clone the response. A response is a stream
                                    // and because we want the browser to consume the response
                                    // as well as the cache consuming the response, we need
                                    // to clone it so we have two streams.
                                    var responseToCache = response.clone();

                                    caches.open(filecache)
                                        .then((cache) => {
                                            cache.put(evt.request, responseToCache);
                                        });
                                }

                                return response;
                            }
                        ,(fail) => null);

                        return cache_response ? cache_response : serv_response;
                    })
            );
        }
        else {
            evt.respondWith(
                caches.match(evt.request)
                    .then((response) => {
                        // Cache hit - return response
                        if (response) {
                            return response;
                        }

                        return fetch(evt.request).then(
                            (response) => {
                                // Check if we received a valid response
                                if(!response || response.status !== 200 || (response.type !== 'basic' && response.type !== 'cors')) {
                                    return response;
                                }

                                // IMPORTANT: Clone the response. A response is a stream
                                // and because we want the browser to consume the response
                                // as well as the cache consuming the response, we need
                                // to clone it so we have two streams.
                                var responseToCache = response.clone();

                                caches.open(filecache)
                                    .then((cache) => {
                                        cache.put(evt.request, responseToCache);
                                    });

                                return response;
                            }
                        );
                    })
            );
        }
    }
});