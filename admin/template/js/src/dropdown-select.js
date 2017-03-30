/*! ========================================================================
 * dropselect: dropdown-select.js.js v0.0.0
 * change the comportement of a dropdown to be the same as the comportement of a select
 * ========================================================================
 * Copyright 2015, Salvatore Di Salvo (disalvo-infographiste[dot].be)
 * ======================================================================== */

(function ($) {
    'use strict';

    // DROPDOWN PUBLIC CLASS DEFINITION
    // ====================================

    var dropselect = function (element, options) {
        this.element = $(element)
        this.options = $.extend({}, this.defaults(), options)
        this.structure = $.extend({}, this.parts())
        this.keywords = null
        this.init()
    }

    dropselect.VERSION = '0.0.0'

    dropselect.DEFAULTS = {}

    dropselect.prototype.parts = function () {
        return {
            button : $('button', this.element),
            items : $('li > a', this.element),
            display : $('.selected', this.element),
            input : $(this.options.input)
        }
    }

    dropselect.prototype.defaults = function () {
        return {
            input : this.element.attr('data-input')
        }
    }

    dropselect.prototype.init = function () {
        var $dds = this;

        this.structure.items.on('click',function(e){
            e.preventDefault();
            if(!$(this).parent().hasClass('disabled')) {
                var val = $(this).data('value'),
                    name = $(this).text();
                $dds.structure.display.text(name);
                $dds.structure.items.parent().removeClass('active');
                $(this).parent().addClass('active');
                $dds.structure.input.val(val);
            }
        });
    }

    // DROPDOWN PLUGIN DEFINITION
    // ==============================

    function Plugin() {
        var arg = arguments;
        return this.each(function () {
            var $this = $(this),
                data = $this.data('dropselect'),
                method = arg[0];

            if (typeof(method) == 'object' || !method) {
                var options = typeof method == 'object' && method;
                $this.data('dropselect', (data = new dropselect(this, options)));
            } else {
                if (data[method]) {
                    method = data[method];
                    arg = Array.prototype.slice.call(arg, 1);
                    if (arg != null || arg != undefined || arg != [])  method.apply(data, arg);
                } else {
                    $.error('Method ' + method + ' does not exist on jQuery.dropselect');
                    return this;
                }
            }
        })
    }

    var old = $.fn.dropselect

    $.fn.dropselect = Plugin
    $.fn.dropselect.Constructor = dropselect

    // DROPDOWN NO CONFLICT
    // ========================

    $.fn.toggle.noConflict = function () {
        $.fn.dropselect = old
        return this
    }

    // DROPDOWN DATA-API
    // =====================

    $(function () {
        $('.dropdown-select').dropselect();
    });
}(jQuery));