/**
 * MAGIX CMS
 * @category   Slideshow
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2009 - 2013 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com, http://www.magix-dev.be
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * @author Gérits Aurélien <aurelien[at]magix-cms.com>|<contact[at]magix-dev.be>
 * Slideshow
 *
 */
var imgdrop = (function ($) {
    'use strict';
    /**
     * @param input
     */
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            $('#preview').removeClass('no-img').addClass('preview');
            $('#drop-zone').removeClass('no-img');
        }
    }

    function initDropZone() {
        var dropZoneId = "drop-zone";
        var buttonId = "clickHere";
        var mouseOverClass = "mouse-over";
        var btnSend = $("#" + dropZoneId).find('button[type="submit"]');

        var dropZone = $("#" + dropZoneId);
        var ooleft = dropZone.offset().left;
        var ooright = dropZone.outerWidth() + ooleft;
        var ootop = dropZone.offset().top;
        var oobottom = dropZone.outerHeight() + ootop;
        var inputFile = dropZone.find('input[type="file"]');
        dropZone.off().on("dragover", function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.addClass(mouseOverClass);
            var x = e.pageX;
            var y = e.pageY;

            if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
                inputFile.offset({ top: y - 15, left: x - 100 });
            } else {
                inputFile.offset({ top: -400, left: -400 });
            }

        });

        if (buttonId !== "") {
            var clickZone = $("#" + buttonId);

            var oleft = clickZone.offset().left;
            var oright = clickZone.outerWidth() + oleft;
            var otop = clickZone.offset().top;
            var obottom = clickZone.outerHeight() + otop;

            $("#" + buttonId).mousemove(function (e) {
                var x = e.pageX;
                var y = e.pageY;
                if (!(x < oleft || x > oright || y < otop || y > obottom)) {
                    inputFile.offset({ top: y - 15, left: x - 160 });
                } else {
                    inputFile.offset({ top: -400, left: -400 });
                }
            });
        }

        $("#" + dropZoneId).find('input[type="file"]').change(function(){
            var inputVal = $(this).val();
            if(inputVal === '') {
                $(btnSend).prop('disabled',true);
            } else {
                $(btnSend).prop('disabled',false);
            }
        });

        document.getElementById(dropZoneId).addEventListener("drop", function (e) {
            $("#" + dropZoneId).removeClass(mouseOverClass);
        }, true);
    }

    function resetInput() {
        $("#img").val('');
        $('#preview').attr('src', '#').addClass('no-img').removeClass('preview');
    }

    return {
        run: function() {
            $('.inputfile').each(function() {
                // Firefox bug fix
                $(this).on( 'focus', function(){ $(this).addClass( 'has-focus' ); });
                $(this).on( 'blur', function(){ $(this).removeClass( 'has-focus' ); });
            });

            $("#img").change(function(){
                readURL(this);
                if(typeof $('.resetImg') !== 'undefined') {
                    $('.resetImg').removeClass('hide');
                }
            });

            $('.resetImg').click(function(e){
                e.preventDefault();
                $(this).addClass('hide');
                resetInput();
                return false;
            });

            initDropZone();
        },
        reset: function() {
            resetInput();
        }
    };
})(jQuery);

$(document).ready(function(){
    'use strict';

    imgdrop.run();

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if($(e.target).attr('href') === '#image') {
            imgdrop.run();
        }
    });
});