/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
'use strict';

window.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('resize', viewportUnitValue);
    viewportUnitValue();

    if(typeof LazyLoad !== "undefined") {
        let lazyLoadInstance = new LazyLoad({
            elements_selector: ".lazyload"
        });
    }

    // *** target_blank
    document.querySelectorAll("a.targetblank").forEach( function(i) {
        i.addEventListener('click',function(e) {
            e.preventDefault();
            window.open(i.getAttribute('href'));
            return false;
        })
    });

    // *** Smooth Scroll to Top
    document.querySelectorAll(".toTop").forEach( function(i) {
        if(i.dataset.toggle === 'tooltip') {
            new Tooltip(i);
        }
        i.addEventListener('click',function(e) {
            e.preventDefault();
            scrollToTop(450);
            return false;
        })
    });

    // *** add the class 'open' on a collapse button when his collapsible element is opened
    document.querySelectorAll('[data-toggle="collapse"]').forEach( function(i) {
        let parent = i.dataset.parent || false,
            target = i.dataset.target ? i.dataset.target : i.getAttribute('href');
        i.collapse = parent ? new Collapse(i,{parent: document.querySelector(parent)}) : new Collapse(i);
        /*if(parent && document.querySelector(parent).querySelector('[data-toggle="collapse"]') === i) {
            //console.log(i.collapse);
            i.collapse.show();
        }*/
        //function toggle(i,e,t) { if(e.target === t) i.classList.toggle('open',/^shown.*/.test(e.type)); }
        function toggle(i, e) {
            //if (e.target === t) {
            let shown = /^shown.*/.test(e.type);
            i.classList.toggle('open', shown);
            if (e.target.classList.contains('has-overlay')) {
                //let overlay = document.getElementById('overlay');
                let overlay = e.target.querySelector('.overlay');
                overlay.classList.toggle('active', shown);
                overlay.addEventListener('click', (e) => {
                    i.collapse.hide();
                });
            }
            //}
        }
        if(typeof target === 'string' && /^#.+/.test(target)) {
            document.querySelector(target).addEventListener('shown.bs.collapse', (e) => { toggle(i,e); });
            document.querySelector(target).addEventListener('hidden.bs.collapse', (e) => { toggle(i,e); });
        }
    });

    // *** Enable the use of collapsible elements in a dropdown context
    document.querySelectorAll('.dropdown [data-toggle="collapse"]').forEach( function(i) {
        i.addEventListener( 'click', function(e) {
            e.stopPropagation();
            //e.preventDefault();
            //console.log(i.dataset.target);
            //console.log(i.Collapse);
            i.Collapse.toggle(e);
        });
    });

    if(SimpleLightbox !== undefined) {
        let slOptions = {
            closeBtnContent: '<i class="material-icons ico ico-close"></i>',
            nextBtnContent: '<i class="material-icons ico ico-keyboard_arrow_left"></i>',
            prevBtnContent: '<i class="material-icons ico ico-keyboard_arrow_right"></i>'
        };
        new SimpleLightbox(Object.assign({},slOptions,{
            elements: '.img-zoom',
        }));
        new SimpleLightbox(Object.assign({},slOptions,{
            elements: '.img-gallery',
        }));
    }

    // *** Tiny Slider Carousel init
    let tnsOptions = {
        loop: true,
        gutter: 30,
        nav: false,
        controls: true,
        lazyLoad: true,
        controlsText: [
            '<i class="material-icons ico ico-keyboard_arrow_left" aria-hidden="true"></i>',
            '<i class="material-icons ico ico-keyboard_arrow_right" aria-hidden="true"></i>'
        ]
    };
    if(document.getElementsByClassName('slideshow').length > 0 && tns !== undefined) {
        let slidec = document.querySelector('.slideshow');
        let slideshow = tns(Object.assign({},tnsOptions,{
            container: '.slideshow',
            mode: 'gallery',
            items: 1,
            gutter: 0,
            speed: 500,
            autoplay: true,
            autoplayButtonOutput: false,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            controls: false
        }));
    }
    if(document.getElementsByClassName('thumbs').length > 0 && tns !== undefined) {
        let thumbs = tns(Object.assign({},tnsOptions,{
            container: '.thumbs',
            loop:false,
            responsive:{
                0:{
                    items:2,
                    gutter: 7
                },
                536:{
                    items: 3,
                    gutter: 14
                },
                768:{
                    items: 3,
                    gutter: 21
                },
                1400:{
                    items: 3,
                    gutter: 28
                }
            }
        }));

        document.querySelectorAll('.show-img').forEach(function(i){
            i.addEventListener('click',(e) => {
                e.preventDefault();
                document.querySelectorAll('.big-image > a, .big-image > div').forEach((container) => {
                    container.style.zIndex = -1;
                    container.style.opacity = 0;
                    container.querySelectorAll('img, iframe, video').forEach((item) => {
                        item.style.visibility = 'hidden';
                        item.style.display = 'none';
                    });
                });

                let target = document.querySelectorAll(i.dataset.target)[0];
                target.style.zIndex = 1;
                target.style.opacity = 1;
                target.querySelectorAll('img, iframe, video').forEach((item) => {
                    item.style.visibility = 'visible';
                    item.style.display = 'block';
                });
                return false;
            });
        });
    }
    // *** Owl Carousel in plugins
    // *** Add here the content of the public.js file of the plugins using owl-carousel
    // Sélectionne tous les éléments avec la classe "animate-on-scroll"
    const elements = document.querySelectorAll(".animate-on-scroll");
    // Options pour l'observateur d'intersection
    const options = {
        threshold: 0.35
    };
    // Instanciation de l'observateur d'intersection
    const observer = new IntersectionObserver(function (entries, observer) {
        // Boucle sur chaque entrée pour traiter les intersections
        entries.forEach(entry => {
            // Si l'entrée est en train d'intersecter avec la zone visible
            if (entry.isIntersecting) {
                // Ajouter la classe "is-visible" pour déclencher l'animation
                entry.target.classList.add("is-visible");
                // Cesser d'observer cet élément
                observer.unobserve(entry.target);
            }
        });
    }, options);
    // Observer chaque élément
    elements.forEach(element => {
        observer.observe(element);
    });
});