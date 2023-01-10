/*!
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of tinyMCE.
 # YouTube for tinyMCE
 # Copyright (C) 2011 - 2019  Gerits Aurelien <aurelien[at]magix-cms[dot]com>
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 */
(function () {
    var loremipsum = (function () {
        'use strict';
        //tinymce.PluginManager.requireLangPack("youtube");
        tinymce.PluginManager.add("loremipsum", function (editor, url) {
            function li(){
                return 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
            }
            /*
            Add a custom icon to TinyMCE
             */
            editor.ui.registry.addIcon('lorem-ipsum', '<svg width="24" height="24"><use xlink:href="'+url+'/img/lorem-ipsum.svg#loremipsum"></use></svg>');
            // Add a button into the toolbar
            editor.ui.registry.addButton('loremipsum', {
                icon: 'lorem-ipsum',
                title: 'loremipsum',
                tooltip: 'loremipsum',
                onAction: () => {
                    editor.insertContent(li());
                }
            });
            // Add a button into the menu bar
            editor.ui.registry.addMenuItem('loremipsum', {
                icon: 'lorem-ipsum',
                title: 'loremipsum',
                text : 'loremipsum',
                context: 'insert',
                onAction: () => {
                    editor.insertContent(li());
                }
            });
            // Return details to be displayed in TinyMCE's "Help" plugin, if you use it
            // This is optional.
            return {
                getMetadata: function () {
                    return {
                        name: "Loremipsum Plugin",
                        url: "https://github.com/gtraxx/tinymce-plugin-youtube"
                    };
                }
            };
        });
    }());
})();