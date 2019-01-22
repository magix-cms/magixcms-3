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
                let caption = this.$currentTarget.find('img').attr('alt');
                let closebtn = this.$instance.find('button');
                this.$instance.find('.caption').remove();
                this.$instance.find('figure').remove();
                this.$content
                    .appendTo(this.$instance.find('.featherlight-content'))
                    .wrapAll('<figure />');
                $('<p />')
                    .text(caption)
                    .appendTo(this.$instance.find('.featherlight-content figure'))
                    .wrapAll('<figcaption class="caption">');
                $(closebtn[0]).prependTo(this.$instance.find('.featherlight-content .caption'));
            };
            $.featherlight.prototype.afterContent = afterContent;

            $('.img-zoom').featherlight();

            if($.featherlightGallery !== undefined) {
                $.featherlightGallery.prototype.afterContent = afterContent;
                $.featherlightGallery.prototype.previousIcon = '<span class="fa fa-angle-left"></span>';
                $.featherlightGallery.prototype.nextIcon = '<span class="fa fa-angle-right"></span>';
                $('.img-gallery').featherlightGallery();
            }
            else {
                $('.img-gallery').featherlight();
            }
        }
    });
}(jQuery);