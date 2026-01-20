/**
 * LazyLoad Image for tinyMCE
 * Version 2.0.0 - Compatible TinyMCE 7
 * Developed by Gerits Aurélien - Magix CMS
 */
(function () {
    'use strict';
    tinymce.PluginManager.requireLangPack("lazyloadimage");
    tinymce.PluginManager.add('lazyloadimage', function (editor, url) {

        const _ = (text) => editor.translate(text);

        const toggleLazyLoad = () => {
            const el = editor.selection.getNode();

            // On vérifie qu'on a bien une image
            if (el && el.nodeName === 'IMG') {

                // Si l'image a déjà la classe, on propose de l'enlever (toggle)
                if (editor.dom.hasClass(el, 'lazyload')) {
                    editor.dom.removeClass(el, 'lazyload');
                    editor.dom.setAttrib(el, 'loading', null); // Retire l'attribut

                    editor.notificationManager.open({
                        text: _('Lazy loading removed'),
                        type: 'info',
                        timeout: 2000
                    });
                } else {
                    // Ajout du Lazy Loading
                    editor.dom.addClass(el, 'lazyload');
                    editor.dom.setAttrib(el, 'loading', 'lazy');

                    editor.notificationManager.open({
                        text: _('Lazy loading applied'),
                        type: 'success',
                        timeout: 2000
                    });
                }

                editor.nodeChanged();
            } else {
                editor.notificationManager.open({
                    text: _('Please select an image'),
                    type: 'warning',
                    timeout: 2000
                });
            }
        };

        // --- UI REGISTRY ---

        // Icône personnalisée (SVG intégré pour éviter les problèmes de lien)
        editor.ui.registry.addIcon('lazyloading', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>');

        // Ajout d'un bouton de barre d'outils (plus pratique qu'un menu seul)
        editor.ui.registry.addButton('lazyloadimage', {
            icon: 'lazyloading',
            tooltip: _('LazyImg'),
            onAction: toggleLazyLoad,
            onSetup: (api) => {
                const nodeChangeHandler = () => {
                    const isImg = editor.selection.getNode().nodeName === 'IMG';
                    api.setEnabled(isImg);
                    api.setActive(isImg && editor.dom.hasClass(editor.selection.getNode(), 'lazyload'));
                };
                editor.on('NodeChange', nodeChangeHandler);
                return () => editor.off('NodeChange', nodeChangeHandler);
            }
        });

        // Entrée de menu
        editor.ui.registry.addMenuItem('lazyloadimage', {
            icon: 'lazyloading',
            text: _('LazyImg'),
            onAction: toggleLazyLoad
        });
    });
})();