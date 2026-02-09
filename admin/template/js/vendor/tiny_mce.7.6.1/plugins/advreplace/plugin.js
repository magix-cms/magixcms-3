/**
 * Advanced Source Replace
 * Version: 2.0
 * Author: Gerits Aurélien
 * Compatibility: TinyMCE 6 & 7
 */
(function () {
    'use strict';

    // Charge le pack de langue situé dans le dossier /langs
    tinymce.PluginManager.requireLangPack("advreplace");

    tinymce.PluginManager.add('advreplace', function (editor, url) {
        const _ = (text) => editor.translate(text);
        let formulaPresets = [];

        /**
         * Chargement du fichier JSON externe
         * Le fichier doit se trouver dans le même dossier que plugin.js
         */
        fetch(`${url}/formulas.json`)
            .then(response => {
                if (!response.ok) throw new Error("JSON file not found");
                return response.json();
            })
            .then(data => {
                formulaPresets = data;
            })
            .catch(err => {
                console.warn("AdvReplace: Could not load formulas.json. Using manual mode only.", err);
            });

        /**
         * Logique de traitement du contenu
         */
        const performReplace = (data) => {
            if (!data.find) return false;

            try {
                // Récupération du contenu (format raw pour éviter les nettoyages automatiques pendant le processus)
                let content = editor.getContent({ format: 'raw' });
                const flags = data.caseSensitive ? 'g' : 'gi';
                const re = new RegExp(data.find, flags);
                let replacement = data.replace || '';

                if (!re.test(content)) return false;

                let newContent = content.replace(re, replacement);

                // Nettoyage profond : suppression des espaces doubles à l'intérieur des balises
                newContent = newContent.replace(/\s{2,}(?=[^>]*>)/g, ' ');

                if (content !== newContent) {
                    editor.undoManager.add();
                    editor.setContent(newContent);

                    editor.notificationManager.open({
                        text: _('Cleanup completed'),
                        type: 'success',
                        timeout: 1500
                    });
                    return true;
                }
                return false;
            } catch (e) {
                editor.windowManager.alert(_('Regex Error: ') + e.message);
                return false;
            }
        };

        /**
         * Interface du plugin
         */
        const openDialog = () => {
            editor.windowManager.open({
                title: _('Advanced Source Cleaner'),
                body: {
                    type: 'panel',
                    items: [
                        {
                            type: 'input',
                            name: 'searchFilter',
                            label: _('1. Search for a preset'),
                            placeholder: _('Type to filter formulas...')
                        },
                        {
                            type: 'selectbox',
                            name: 'presetList',
                            label: _('2. Select a formula'),
                            items: [
                                { text: _('--- Waiting for input ---'), value: '' },
                                // On traduit le nom affiché mais on garde la valeur brute pour la recherche
                                ...formulaPresets.map(f => ({ text: _(f.name), value: f.name }))
                            ]
                        },
                        {
                            type: 'input',
                            name: 'find',
                            label: _('3. Regex to find'),
                            placeholder: 'ex: \\s+data-path=[\"\\\'][^\"\\\']*[\"\\\']'
                        },
                        {
                            type: 'input',
                            name: 'replace',
                            label: _('4. Replace with'),
                            placeholder: _('Leave empty to remove')
                        },
                        {
                            type: 'grid',
                            columns: 2,
                            items: [
                                { type: 'checkbox', name: 'caseSensitive', label: _('Case sensitive') },
                                { type: 'checkbox', name: 'showSource', label: _('Show source code after') }
                            ]
                        }
                    ]
                },
                buttons: [
                    { type: 'cancel', text: _('Cancel') },
                    { type: 'submit', text: _('Apply'), primary: true }
                ],
                onChange: (api, details) => {
                    const data = api.getData();

                    // Filtrage dynamique : met à jour les champs si une correspondance est trouvée
                    if (details.name === 'searchFilter') {
                        const query = data.searchFilter.toLowerCase();
                        const filtered = formulaPresets.filter(f => f.name.toLowerCase().includes(query));

                        if (filtered.length > 0) {
                            api.setData({
                                presetList: filtered[0].name,
                                find: filtered[0].find,
                                replace: filtered[0].replace
                            });
                        }
                    }

                    // Sélection manuelle dans la liste
                    if (details.name === 'presetList') {
                        const selected = formulaPresets.find(f => f.name === data.presetList);
                        if (selected) {
                            api.setData({
                                find: selected.find,
                                replace: selected.replace
                            });
                        }
                    }
                },
                onSubmit: (api) => {
                    const data = api.getData();
                    const hasChanged = performReplace(data);
                    api.close();

                    // Ouverture de l'éditeur de code si l'option est cochée
                    if (data.showSource && hasChanged) {
                        setTimeout(() => {
                            if (editor.queryCommandSupported('mceCodeEditor')) {
                                editor.execCommand('mceCodeEditor');
                            } else {
                                editor.execCommand('code');
                            }
                        }, 250);
                    } else if (!hasChanged && data.find) {
                        editor.notificationManager.open({
                            text: _('No matches found'),
                            type: 'warning',
                            timeout: 2000
                        });
                    }
                }
            });
        };

        // Enregistrement de l'icône (Style Outline)
        const iconSvg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 14L16.5 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 11C15 13.2091 13.2091 15 11 15C8.79086 15 7 13.2091 7 11C7 8.79086 8.79086 7 11 7C13.2091 7 15 8.79086 15 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 7L2 12L4 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 7L22 12L20 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        editor.ui.registry.addIcon('advreplace', iconSvg);

        // Ajout du bouton à la barre d'outils
        editor.ui.registry.addButton('advreplace', {
            icon: 'advreplace',
            tooltip: _('Advanced Source Cleaner'),
            onAction: openDialog
        });

        // Ajout au menu "Outils"
        editor.ui.registry.addMenuItem('advreplace', {
            icon: 'advreplace',
            text: _('Advanced Source Cleaner'),
            onAction: openDialog
        });
    });
})();