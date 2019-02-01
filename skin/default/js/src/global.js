/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license     Dual licensed under the MIT or GPL Version 3 licenses.
 * @version     1.0
 * @author      Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */

// Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
// Conceived by Robert Kent, James Bavington & Tom Foyster
// Modified by Simon Freytag for syntax, namespace, jQuery and Bootstrap
// Modified by Salvatore Di Salvo for optimisation and Magix CMS

const C = {
    createCookie: function() {
        let date = new Date();
        date.setTime(date.getTime() + (365*24*60*60*1000));
        let expires = date.toGMTString();
        document.cookie = 'complianceCookie=on; expires=' + expires + '; path=/';
        $("#cookies").removeClass('in').addClass('hide');
    },

    checkCookie: function() {
        let nameEQ = 'complianceCookie=';
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

    init: function() {
        if (this.checkCookie() !== 'on') {
            $("#cookies").removeClass('hide');
        }
    }
};

+function ($) {
    'use strict';

    $(window).on('load', function () {
        C.init(); // Cookie EU Law

        // *** target_blank
        $('a.targetblank').click( function() {
            window.open($(this).attr('href'));
            return false;
        });

        // *** Smooth Scroll to Top
        $('.toTop').click( function() {
            $('html, body').animate({ scrollTop: 0 }, 450);
            return false;
        });

        // *** add the class 'open' on a collapse button when his collapsible element is opened
        $('[data-toggle="collapse"]').each(function(){
            let self = $(this), target = $($(this).data('target'));

            target.on('hidden.bs.collapse',function(){
                if(self.hasClass('open') && !target.hasClass('collapse in')){
                    self.removeClass('open');
                }
            });

            target.on('shown.bs.collapse',function(){
                if(!self.hasClass('open') && target.hasClass('in')){
                    self.addClass('open');
                }
            });
        });

        // *** Enable the use of collapsible elements in a dropdown context
        $('.dropdown [data-toggle="collapse"]').click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).closest(".dropdown").addClass("open");
            $($(this).data("target")).collapse('toggle');
        });

        // *** featherlight lightbox init
        if($.featherlight !== undefined) {
            let afterContent = function () {
                let g = $.featherlightGallery !== undefined;
                let caption = this.$currentTarget.data('caption') ? this.$currentTarget.data('caption') : this.$currentTarget.attr('title');
                let closebtn = this.$instance.find('button');
                this.$instance.find('.caption').remove();
                this.$instance.find('.figure').remove();
                if(g) {
                    this.$instance.find('.featherlight-previous').remove();
                    this.$instance.find('.featherlight-next').remove();
                }
                this.$content
                    .appendTo(this.$instance.find('.featherlight-content'))
                    .wrapAll('<div class="figure" />');
                $('<p />')
                    .text(caption)
                    .appendTo(this.$instance.find('.featherlight-content .figure'))
                    .wrapAll('<div class="caption">');
                $(closebtn[0]).prependTo(this.$instance.find('.featherlight-content .caption'));
                if(g) {
                    $(closebtn[0]).after(this.createNavigation('previous')).after(this.createNavigation('next'));
                }
            };

            $.featherlight.prototype.afterContent = afterContent;
            $('.img-zoom').featherlight();
            if($.featherlightGallery !== undefined) {
                $.featherlightGallery.prototype.previousIcon = '<i class="material-icons">keyboard_arrow_left</i>';
                $.featherlightGallery.prototype.nextIcon = '<i class="material-icons">keyboard_arrow_right</i>';
                $.featherlightGallery.prototype.afterContent = afterContent
                $('.img-gallery').featherlightGallery();
            }
            else {
                $('.img-gallery').featherlight();
            }
        }

        // *** Owl Carousel init
        let owlOptions = {
            loop: true,
            margin: 30,
            responsiveClass: true,
            nav: true,
            dots: false,
            lazyLoad:true,
            navElement: 'a',
            navText: [
                '<i class="material-icons ico ico-keyboard_arrow_left" aria-hidden="true">keyboard_arrow_left</i>',
                '<i class="material-icons ico ico-keyboard_arrow_right" aria-hidden="true">keyboard_arrow_right</i>'
            ]
        };
        if($(".owl-slideshow").length > 0 && $.fn.owlCarousel !== undefined) {
            $(".owl-slideshow > .owl-carousel").owlCarousel(Object.assign({},owlOptions,{
                items: 1,
                margin: 0,
                dots: true,
                animateOut: 'fadeOut',
                dotsContainer: '.owl-slideshow-dots',
                navContainer: '.owl-slideshow-nav'
            }));
        }
        if($(".thumbs").length > 0 && $.fn.owlCarousel !== undefined) {
            $(".thumbs").owlCarousel(Object.assign({},owlOptions,{
                margin: 5,
                items:3
            }));
            // *** for gallery pictures
            $(".show-img").off('click').click(function(){
                $(".big-image a").animate({ opacity: 0, 'z-index': -1 }, 200);
                $($(this).data('target')).animate({ opacity: 1, 'z-index': 1 }, 200);
                return false;
            });
        }
        // *** Owl Carousel in plugins
        // *** Uncomment this block if you're using the mainsectors plugin
        /*if($(".owl-cat").length > 0 && $.fn.owlCarousel !== undefined) {
            $(".owl-cat").owlCarousel(Object.assign({},owlOptions,{
                responsive:{
                    0:{
                        items:1,
                        margin: 0
                    },
                    480:{
                        items:2,
                        margin: 0
                    },
                    768:{
                        items:2,
                        margin: 30
                    },
                    992:{
                        items:3,
                        margin: 30
                    }
                }
            }));
        }*/
        // *** Uncomment this block if you're using the homecatalog plugin
        /*if($(".owl-products").length > 0 && $.fn.owlCarousel !== undefined) {
            $(".owl-products").owlCarousel(Object.assign({},owlOptions,{
                responsive:{
                    0:{
                        items:1,
                        margin: 0
                    },
                    480:{
                        items:2,
                        margin: 0
                    },
                    768:{
                        items:2,
                        margin: 30
                    },
                    992:{
                        items:3,
                        margin: 30
                    },
                    1200:{
                        items:4,
                        // slideBy:2,
                        margin: 30
                    }
                }
            }));
        }*/
        // *** Uncomment this block if you're using the homebrands plugin
        /*if($(".owl-brands").length > 0 && $.fn.owlCarousel !== undefined) {
            $(".owl-brands").owlCarousel(Object.assign({},owlOptions,{
                margin: 0,
                dots: true,
                nav: false,
                autoplay: true,
                autoplayHoverPause: true,
                autoplayTimeout: 5000,
                responsive:{
                    0:{
                        items:1,
                        margin: 0
                    },
                    480:{
                        items:2,
                        margin: 0
                    },
                    768:{
                        items:3,
                        margin: 30
                    },
                    992:{
                        items:5,
                        margin: 30
                    },
                    1200:{
                        items:6,
                        margin: 30
                    }
                }
            }));
        }*/
    });
}(jQuery);