/**
 * Advanced Source Replace - Final Version
 * Compatibility: TinyMCE 6 & 7
 * Default Language: English
 */
(function () {
    'use strict';
    tinymce.PluginManager.requireLangPack("advreplace");
    tinymce.PluginManager.add('advreplace', function (editor) {
        const _ = (text) => editor.translate(text);

        const performReplace = (data) => {
            if (!data.find) return;

            try {
                let content = editor.getContent({ format: 'raw' });
                const flags = data.caseSensitive ? 'g' : 'gi';
                const re = new RegExp(data.find, flags);

                let replacement = data.replace || '';
                let newContent = content.replace(re, replacement);

                // Deep clean: remove extra spaces left inside tags
                newContent = newContent.replace(/\s{2,}(?=[^>]*>)/g, ' ');

                if (content !== newContent) {
                    editor.undoManager.add();
                    editor.setContent(newContent);

                    editor.notificationManager.open({
                        text: _('Source code cleanup completed'),
                        type: 'success',
                        timeout: 1500
                    });

                    // Open Code Editor logic
                    if (data.showSource) {
                        setTimeout(() => {
                            editor.focus(); // Crucial: regain focus
                            try {
                                if (editor.queryCommandSupported('mceCodeEditor')) {
                                    editor.execCommand('mceCodeEditor');
                                } else {
                                    editor.execCommand('code');
                                }
                            } catch (err) {
                                console.warn("Could not open code editor:", err);
                            }
                        }, 300);
                    }
                } else {
                    editor.notificationManager.open({
                        text: _('No match found'),
                        type: 'warning',
                        timeout: 2000
                    });
                }
            } catch (e) {
                editor.windowManager.alert(_('Regex Error: ') + e.message);
            }
        };

        const openDialog = () => {
            editor.windowManager.open({
                title: _('Source Code Cleaner'),
                body: {
                    type: 'panel',
                    items: [
                        {
                            type: 'input',
                            name: 'find',
                            label: _('Find (Regex)'),
                            placeholder: 'ex: \\s+(data-)?(path-to-node|index-in-node)=["\'][^"\']*["\']'
                        },
                        {
                            type: 'input',
                            name: 'replace',
                            label: _('Replace with'),
                            placeholder: _('Leave empty to remove')
                        },
                        {
                            type: 'checkbox',
                            name: 'caseSensitive',
                            label: _('Case sensitive')
                        },
                        {
                            type: 'checkbox',
                            name: 'showSource',
                            label: _('Check source code after application')
                        }
                    ]
                },
                buttons: [
                    { type: 'cancel', text: _('Cancel') },
                    { type: 'submit', text: _('Apply'), primary: true }
                ],
                onSubmit: (api) => {
                    const data = api.getData();
                    api.close();
                    performReplace(data);
                }
            });
        };

        // Soft Icon Design (Outline style)
        const iconSvg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 14L16.5 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 11C15 13.2091 13.2091 15 11 15C8.79086 15 7 13.2091 7 11C7 8.79086 8.79086 7 11 7C13.2091 7 15 8.79086 15 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 7L2 12L4 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 7L22 12L20 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

        editor.ui.registry.addIcon('advreplace', iconSvg);

        editor.ui.registry.addButton('advreplace', {
            icon: 'advreplace',
            tooltip: _('Advanced Source Cleaner'),
            onAction: openDialog
        });

        editor.ui.registry.addMenuItem('advreplace', {
            icon: 'advreplace',
            text: _('Advanced Source Cleaner'),
            onAction: openDialog
        });
    });
})();