/**
 * MAGIX CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 */

// Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
// Conceived by Robert Kent, James Bavington & Tom Foyster
// Modified by Simon Freytag for syntax, namespace, jQuery and Bootstrap
// Modified by Salvatore Di Salvo for optimisation and Magix CMS

+function ($) {
    'use strict';

    const C = {
        block: $('#cookies'),
        btn: $('#cookies button'),
        createCookie: function() {
            let date = new Date();
            date.setTime(date.getTime() + (365*24*60*60*1000));
            document.cookie = 'complianceCookie=on; expires=' + date.toGMTString() + '; path=/';
            C.block.removeClass('in').addClass('hide');
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
            if (this.checkCookie() !== 'on') C.block.removeClass('hide');
            C.btn.click(this.createCookie);
        }
    };

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
            let self = $(this);

            $(self.data('target')).on('shown.bs.collapse hidden.bs.collapse',function(e){
                if(e.target === this) self.toggleClass('open',e.type === 'shown');
            });
        });

        // *** Enable the use of collapsible elements in a dropdown context
        $('.dropdown [data-toggle="collapse"]').click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            //$(this).closest(".dropdown").addClass("open");
            $($(this).data("target")).collapse('toggle');
        });

        // *** featherlight lightbox init
        if($.featherlight !== undefined) {
            let afterContent = function () {
                let trg = this.$currentTarget,
                    g = $.featherlightGallery !== undefined && trg.hasClass('img-gallery'),
                    fl = '.featherlight',
                    flc = fl+'-content',
                    caption = trg.data('caption') ? trg.data('caption') : trg.attr('title'),
                    closebtn = this.$instance.find('button')[0];

                this.$instance.find('.caption, .figure'+(g ? ', '+fl+'-previous, '+fl+'-next' : '')).remove();

                this.$content
                    .appendTo(this.$instance.find(flc))
                    .wrapAll('<div class="figure" />');

                $('<div class="caption">')
                    .append($(closebtn),$('<p />').text(caption))
                    .appendTo(this.$instance.find(flc+' .figure'));

                if(g) $(closebtn).after(this.createNavigation('next'),this.createNavigation('previous'));
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
        if($(".owl-slideshow").length > 0 && $.fn.owlCarousel !== undefined) {
            $(".owl-slideshow > .owl-carousel").owlCarousel(Object.assign({},owlOptions,{
                items: 1,
                margin: 0,
                dots: true,
                dotsData: true,
                autoplay: true,
                autoplayHoverPause: true,
                autoplayTimeout: 5000,
                animateOut: 'fadeOut',
                dotsContainer: '.owl-slideshow-dots',
                navContainer: '.owl-slideshow-nav'
            }));
            $(".owl-slideshow .owl-dot").on('click', function() {
                $(".owl-slideshow > .owl-carousel").trigger('to.owl.carousel', $(this).index());
            });
        }
        // *** Owl Carousel in plugins
        // *** Add here the content of the public.js file of the plugins using owl-carousel
    });
}(jQuery);