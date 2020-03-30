/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */
'use strict';

const C = {
    block: document.getElementById('cookies'),
    btn: document.querySelector('#cookies button'),
    createCookie: function() {
        Cookie.createCookie('complianceCookie');
        C.block.classList.remove('in');
        C.block.classList.add('hide');
    },
    init: function() {
        if (Cookie.checkCookie('complianceCookie') !== 'on') C.block.classList.remove('hide');
        C.btn.addEventListener('click',this.createCookie);
    }
};

window.addEventListener('load', function() {
    if(Cookie !== undefined) {
        C.init();
    }

    let lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazyload"
    });

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
        parent ? new Collapse(i,{parent: document.querySelector(parent)}) : new Collapse(i);
        function toggle(i,e,t) { if(e.target === t) i.classList.toggle('open',/^shown.*/.test(e.type)); }
        if(typeof target === 'string' && /^#.+/.test(target)) {
            document.querySelector(target).addEventListener('shown.bs.collapse', function(e) { toggle(i,e,this); });
            document.querySelector(target).addEventListener('hidden.bs.collapse', function(e) { toggle(i,e,this); });
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
            i.addEventListener('click',function(e){
                e.preventDefault();
                document.querySelectorAll('.big-image a').forEach(function(main){
                    main.style.opacity = 0;
                    main.style.zindex = -1;
                });

                let img = document.querySelectorAll(i.dataset.target)[0];
                img.style.opacity = 1;
                img.style.zindex = 1;
                return false;
            });
        });
    }
    // *** Owl Carousel in plugins
    // *** Add here the content of the public.js file of the plugins using owl-carousel
});