/**
 * MAGIX CMS
 * @copyright MAGIX CMS Copyright (c) 2018,
 * http://www.magix-cms.com, magix-cms.com http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 1.0
 * @author Salvatore Di Salvo <www.disalvo-infographiste.be>
 */

const install = (function ($) {
    'use strict';

    function nextStep(e) {
        $('.form-steps__item').each(function() {
            if($(this).hasClass('form-steps__item--active')) {
                $(this).removeClass('form-steps__item--active').addClass('form-steps__item--completed').next().find('a').data('toggle','tab').attr('data-toggle','tab').on('click',function(){
                    $(this).tab('show');
                });
                if($(this).next().is(':last-child')) {
                    $(this).next().removeClass('form-steps__item--active').addClass('form-steps__item--completed');
                }
                else {
                    $(this).next().addClass('form-steps__item--active');
                }
                let title = document.title;
                title = title.split('|');
                let step = $(this).next().find('.form-steps__item-text').text();
                document.title = step + ' |' + title[1];
                return false;
            }
        });
        $(e).addClass('hide').next().removeClass('hide');
    }

    return {
        init: function () {
            $('[data-toggle="disabled"]').on('click',function(e){
                e.preventDefault();
                return false;
            });
            this.welcome();
        },
        welcome: function () {
            let self = this;
            $('#start').on('click',function(e){
                e.preventDefault();
                nextStep(this);

                $.jmRequest({
                    handler: "ajax",
                    url: 'index.php?action=get&tab=analysis',
                    method: 'get',
                    success: function(d){
                        if(d.result !== null ||d.result !== '') {
                            $('#analysis .spinner-container').fadeOut(400,function(){
                                $('#results').html(d.result).fadeIn();
                                $('[data-toggle="tooltip"]').tooltip();
                                if(d.status) {
                                    $('#goto_config').removeClass('disabled');
                                    self.analysis();
                                }
                            });
                        }
                    }
                });

                $(this).tab('show');
                return false;
            });
        },
        analysis: function () {
            let self = this;
            $('#goto_config').on('click',function(e){
                e.preventDefault();
                nextStep(this);
                $(this).tab('show');

                $('#config-form').validate({
                    ignore: [],
                    onsubmit: true,
                    event: 'submit',
                    submitHandler: function(f,e) {
                        e.preventDefault();
                        $(f).fadeOut(200,function(){
                            $('#configuration .spinner-container').fadeIn(200,function(){
                                $.jmRequest({
                                    handler: "submit",
                                    url: $(f).attr('action'),
                                    method: 'post',
                                    form: $(f),
                                    resetForm: false,
                                    success: function (d) {
                                        $('#configuration .spinner-container').fadeOut(400,function(){
                                            $(f).fadeIn(400,function(){
                                                if(d.notify) {
                                                    $.jmRequest.initbox(d.notify,{ display:true });
                                                    window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);
                                                }

                                                if(d.status) {
                                                    $('input, select', f).addClass('disabled').prop('disabled',true);
                                                    $('[type="submit"]', f).addClass('hide disabled').prop('disabled',true);
                                                    $('#goto_install').removeClass('hide').removeClass('disabled');
                                                    self.configuration();
                                                }
                                            });
                                        });
                                    }
                                });
                            });
                        });
                        return false;
                    }
                });

                return false;
            });
        },
        configuration: function() {
            let self = this;
            $('#goto_install').on('click',function(e){
                e.preventDefault();
                nextStep(this);

                $.jmRequest({
                    handler: "ajax",
                    url: 'index.php?action=install',
                    method: 'get',
                    success: function(d){
                        if(d.result !== null ||d.result !== '') {
                            $('#installation .spinner-container').fadeOut(400,function(){
                                $('#database').fadeIn();
                            });

                            if(d.status) {
                                $('#goto_setting').removeClass('disabled');
                                self.installation();
                            }
                        }
                    }
                });

                $(this).tab('show');
                return false;
            });
        },
        installation: function () {
            let self = this;
            $('#goto_setting').on('click',function(e){
                e.preventDefault();
                nextStep(this);
                $(this).tab('show');

                $('#setting-form').validate({
                    ignore: [],
                    onsubmit: true,
                    event: 'submit',
                    submitHandler: function(f,e) {
                        e.preventDefault();
                        $(f).fadeOut(200,function(){
                            $('#setting .spinner-container').fadeIn(200,function(){
                                $.jmRequest({
                                    handler: "submit",
                                    url: $(f).attr('action'),
                                    method: 'post',
                                    form: $(f),
                                    resetForm: false,
                                    success: function (d) {
                                        $('#setting .spinner-container').fadeOut(400,function(){
                                            $(f).fadeIn();
                                        });

                                        if(d.notify) {
                                            $.jmRequest.initbox(d.notify,{ display:true });
                                            window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, 4000);
                                        }

                                        if(d.status) {
                                            $('input, select', f).addClass('disabled').prop('disabled',true);
                                            $('[type="submit"]', f).addClass('hide disabled').prop('disabled',true);
                                            $('#goto_confirm').removeClass('hide').removeClass('disabled');
                                            self.confirmation();
                                        }
                                    }
                                });
                            });
                        });
                        return false;
                    }
                });

                return false;
            });
        },
        confirmation: function () {
            let self = this;
            $('#goto_confirm').on('click',function(e){
                e.preventDefault();
                nextStep(this);
                $('form-steps > div:last-child').removeClass('form-steps__item--active').addClass('form-steps__item--completed');
                $(this).tab('show');

                $('a.targetblank').click( function() {
                    window.open($(this).attr('href'));
                    return false;
                });
            });
        }
    };
})(jQuery);

window.addEventListener('load', function() {
    install.init();
});