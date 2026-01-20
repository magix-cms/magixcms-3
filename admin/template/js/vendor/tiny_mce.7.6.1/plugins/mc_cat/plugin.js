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

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */

tinymce.PluginManager.requireLangPack('mc_cat');
/**
 * MAGIX CMS - Category Linker
 * Version 2.0.0 - Compatible TinyMCE 7
 */
tinymce.PluginManager.add('mc_cat', function(editor, url) {

    const _ = (text) => editor.translate(text);

    // Récupération de l'icône dans mc_icons.svg
    const iconPath = (typeof baseadmin !== 'undefined')
        ? '/' + baseadmin + '/template/img/tinymce/mc_icons.svg#category'
        : url + '/img/mc_icons.svg#category';

    editor.ui.registry.addIcon('category', `<svg width="24" height="24"><use xlink:href="${iconPath}"></use></svg>`);

    /* Dialogue pour choisir une catégorie */
    const showDialog = () => {
        editor.windowManager.openUrl({
            title: _('mc_cat Title'),
            url: tinymce.baseURL + '/plugins/mc_cat/cat.php',
            width: 800,
            height: 550
        });
    };

    // Bouton barre d'outils
    editor.ui.registry.addButton('mc_cat', {
        icon: 'category',
        tooltip: _('mc_cat Tooltip'),
        onAction: showDialog,
        onSetup: (api) => {
            const nodeChangeHandler = (e) => api.setEnabled(e.element.nodeName !== 'IMG');
            editor.on('NodeChange', nodeChangeHandler);
            return () => editor.off('NodeChange', nodeChangeHandler);
        }
    });

    // Menu "Insertion"
    editor.ui.registry.addMenuItem('mc_cat', {
        icon: 'category',
        text: _('mc_cat Title'),
        onAction: showDialog
    });
});