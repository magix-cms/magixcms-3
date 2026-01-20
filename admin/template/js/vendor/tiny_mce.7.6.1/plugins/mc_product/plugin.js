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

tinymce.PluginManager.requireLangPack('mc_product');
/**
 * MAGIX CMS - Product Linker
 * Version 2.0.0 - Compatible TinyMCE 7
 */
tinymce.PluginManager.add('mc_product', function(editor, url) {

    const _ = (text) => editor.translate(text);

    // Icône spécifique au produit dans votre sprite SVG
    const iconPath = (typeof baseadmin !== 'undefined')
        ? '/' + baseadmin + '/template/img/tinymce/mc_icons.svg#product'
        : url + '/img/mc_icons.svg#product';

    editor.ui.registry.addIcon('product', `<svg width="24" height="24"><use xlink:href="${iconPath}"></use></svg>`);

    /* Dialogue pour choisir un produit */
    const showDialog = () => {
        editor.windowManager.openUrl({
            title: _('mc_product Title'),
            url: tinymce.baseURL + '/plugins/mc_product/product.php',
            width: 800,
            height: 550
        });
    };

    // Bouton de barre d'outils
    editor.ui.registry.addButton('mc_product', {
        icon: 'product',
        tooltip: _('mc_product Tooltip'),
        onAction: showDialog,
        onSetup: (api) => {
            const nodeChangeHandler = (e) => api.setEnabled(e.element.nodeName !== 'IMG');
            editor.on('NodeChange', nodeChangeHandler);
            return () => editor.off('NodeChange', nodeChangeHandler);
        }
    });

    // Menu "Insertion"
    editor.ui.registry.addMenuItem('mc_product', {
        icon: 'product',
        text: _('mc_product Title'),
        onAction: showDialog
    });
});