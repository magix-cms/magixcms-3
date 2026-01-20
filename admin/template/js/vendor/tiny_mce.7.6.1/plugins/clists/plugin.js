/**
 * TinyMCE clists Plugin
 * Version 2.7.0 - Forced I18n Injection
 */
(function () {
    'use strict';
    tinymce.PluginManager.requireLangPack("clists");
    tinymce.PluginManager.add('clists', function (editor) {

        // --- INJECTION FORCÉE DES TRADUCTIONS (au cas où fr_FR.js échoue) ---
        /*const language = editor.getParam('language', 'en');
        if (language === 'fr_FR' || language === 'fr') {
            tinymce.addI18n('fr_FR', {
                'Bullet List': 'Puces rondes',
                'Circle List': 'Puces cercles',
                'Square List': 'Puces carrées',
                'Arrow List': 'Puces flèches',
                'Label List': 'Puces étiquettes',
                'Default': 'Par défaut'
            });
        }*/

        const _ = (text) => editor.translate(text);

        const getBulletStyles = () => {
            return [
                { title: 'Default', style: 'disc' },
                { title: 'Disc', style: 'disc' },
                { title: 'Circle', style: 'circle' },
                { title: 'Square', style: 'square' },
                { title: 'Bullet List', classes: 'bullet-list' },
                { title: 'Circle List', classes: 'circle-list' },
                { title: 'Square List', classes: 'square-list' },
                { title: 'Arrow List', classes: 'arrow-list' },
                { title: 'Label List', classes: 'label-list' }
            ];
        };

        const getAllClasses = () => {
            return getBulletStyles()
                .filter(s => typeof s === 'object' && s.classes)
                .map(s => s.classes);
        };

        const applyFormat = (value) => {
            const styles = getBulletStyles();
            let format = styles.find(s => s.title === value);
            if (!format) format = { style: 'disc' };

            editor.execCommand('InsertUnorderedList', false, format.style ? { 'list-style-type': format.style } : null);

            const listElm = editor.dom.getParent(editor.selection.getNode(), 'ul');
            if (listElm) {
                getAllClasses().forEach(cls => editor.dom.removeClass(listElm, cls));
                if (format.classes) {
                    editor.dom.addClass(listElm, format.classes);
                }
            }
        };

        editor.ui.registry.addSplitButton('bullist', {
            icon: 'unordered-list',
            tooltip: _('Bullet list'),
            onAction: () => editor.execCommand('InsertUnorderedList'),
            onItemAction: (api, value) => applyFormat(value),
            fetch: (callback) => {
                const styles = getBulletStyles();
                const menuItems = styles.map(s => ({
                    type: 'choiceitem',
                    value: s.title,
                    text: _(s.title)
                }));
                callback(menuItems);
            },
            onSetup: (api) => {
                const nodeChangeHandler = (e) => {
                    const listElm = editor.dom.getParent(editor.selection.getNode(), 'ul');
                    api.setActive(!!listElm);
                };
                editor.on('NodeChange', nodeChangeHandler);
                return () => editor.off('NodeChange', nodeChangeHandler);
            }
        });

        return { getMetadata: () => ({ name: "Clists", version: "2.7.0" }) };
    });
})();