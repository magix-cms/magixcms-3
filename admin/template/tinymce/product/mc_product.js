/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */

+function($, baseadmin, undefined) {
    'use strict';
    var mc_product = (function($, baseadmin, undefined){

        /**
         *
         */
        function insertLink() {
            $('#product-link').validate({
                ignore: [],
                onsubmit: true,
                event: 'submit',
                submitHandler: function(f,e) {
                    e.preventDefault();
                    var text = $('#text').val(),
                        title = $('#title').val(),
                        href = $('#url').val();
                    parent.tinymce.activeEditor.insertContent('<a href="'+href+'" title="'+title+'"'+($('#blank').prop('checked') ? 'class="targetblank"' : '')+'>'+text+'</a>');
                    parent.tinymce.activeEditor.windowManager.close();
                    return false;
                }
            });
        }

        /**
         *
         */
        function initDroplang() {
            $('.dropdown-lang').each(function () {
                var self = $(this);
                var items = $(this).find('a[data-toggle="tab"]');

                $(items).off().on('shown.bs.tab', function (e) {
                    $(self).find('.dropdown-menu li.active').removeClass('active');
                    $(this).parent('li').addClass('active');
                    $(self).find('.lang').text($(this).text());
                    $('[data-toggle="toggle"]').each(function(){
                        $(this).bootstrapToggle('destroy');
                    }).each(function(){
                        $(this).bootstrapToggle();
                    });
                });
            });
        }

        /**
         * public function
         */
        return {
            run:function(){
                initDroplang();
                $('.product_id').each(function(){
                    $(this).on('change', function(){
                        var id = $(this).val();
                        var iso = $(this).data('lang');

                        if(typeof id !== 'undefined' && id !== '') {
                            $.jmRequest({
                                type: 'ajax',
                                url: '/'+baseadmin+'/index.php?controller=product&action=getLink&id='+id+'&iso='+iso,
                                method: 'get',
                                success: function(d){
                                    if(typeof d === "object") {
                                        $('#product-link').collapse('show');
                                        $('#text').val(d.name);
                                        $('#title').val(d.name);
                                        $('#url').val(d.url);
                                    }
                                }
                            });
                        }
                        else {
                            $('#product-link').collapse('hide');
                        }
                    });
                });
                insertLink();
            },
            translate:function(varname){
                return parent.tinymce.util.I18n.translate(varname);
            }
        };
    })($, baseadmin);

    /**
     * Execute namespace pages
     */
    $(function(){
        // Init templatewith mustach
        /*var data = {
            "title": mc_pages.translate('mc_pages Title'),
            "category": mc_pages.translate('category'),
            "search": mc_pages.translate('search'),
            "description": mc_pages.translate('mc_pages description')
        };
        //Use jQuery's get method to retrieve the contents of our template file, then render the template.
        $.get('view/forms.html' , function (template) {
            filled = Mustache.render( template, data );
            $('#template-container').append(filled);
             mc_pages.run();
             //Product.call(this, name, price);
        });*/
        mc_product.run();
    });
}(jQuery, baseadmin);