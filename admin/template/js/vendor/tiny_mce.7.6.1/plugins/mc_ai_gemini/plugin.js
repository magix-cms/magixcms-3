tinymce.PluginManager.add('mc_ai_gemini', function(editor, url) {

    // 1. SÉCURITÉ : Vérification de l'activation via MagixCMS
    if (!window.MagixCMS || !window.MagixCMS.ai_enabled) {
        return;
    }

    // 2. ICÔNE : SVG "Sparkles"
    editor.ui.registry.addIcon('gemini-icon', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" fill="#4285F4"/></svg>');

    // 3. LOGIQUE : Dialogue Assistant IA
    const openAiDialog = function() {
        const selectedText = editor.selection.getContent({ format: 'text' });

        const dialog = editor.windowManager.open({
            title: 'Assistant Rédactionnel Magix AI', // Titre plus générique et pro
            size: 'large',
            body: {
                type: 'panel',
                items: [
                    {
                        type: 'grid',
                        columns: 2,
                        items: [
                            {
                                type: 'selectbox',
                                name: 'action_type',
                                label: 'Action',
                                items: [
                                    { text: 'Rédaction / Correction', value: 'write' },
                                    { text: 'Traduire vers...', value: 'translate' }
                                ]
                            },
                            {
                                type: 'selectbox',
                                name: 'language',
                                label: 'Langue cible',
                                items: [
                                    { text: 'Français', value: 'français' },
                                    { text: 'Anglais', value: 'anglais' },
                                    { text: 'Néerlandais', value: 'néerlandais' },
                                    { text: 'Allemand', value: 'allemand' }
                                ]
                            },
                            {
                                type: 'selectbox',
                                name: 'tone',
                                label: 'Ton du texte',
                                items: [
                                    { text: 'Professionnel', value: 'professionnel' },
                                    { text: 'Amical', value: 'amical' },
                                    { text: 'Marketing', value: 'marketing' }
                                ]
                            },
                            {
                                type: 'selectbox',
                                name: 'length',
                                label: 'Longueur',
                                items: [
                                    { text: 'Standard', value: 'standard' },
                                    { text: 'Court', value: 'court' },
                                    { text: 'Détaillé', value: 'long' }
                                ]
                            }
                        ]
                    },
                    {
                        type: 'textarea',
                        name: 'prompt',
                        label: 'Instruction pour l\'IA',
                        placeholder: 'Ex: Résume ce texte, rédige une introduction...'
                    },
                    {
                        type: 'htmlpanel',
                        html: '<div style="margin: 10px 0; border-top: 1px solid #ddd;"></div>'
                    },
                    {
                        type: 'textarea',
                        name: 'result',
                        label: 'Contenu généré (HTML)',
                        placeholder: 'Le résultat s\'affichera ici...',
                        flex: true
                    }
                ]
            },
            buttons: [
                {
                    type: 'custom',
                    name: 'generate_btn',
                    text: 'Générer',
                    primary: true
                },
                {
                    type: 'submit',
                    name: 'insert_btn',
                    text: 'Insérer dans la page',
                    enabled: false
                },
                {
                    type: 'cancel',
                    text: 'Annuler'
                }
            ],
            onAction: function (api, details) {
                if (details.name === 'generate_btn') {
                    const data = api.getData();
                    if (!data.prompt) {
                        editor.notificationManager.open({ text: 'Veuillez saisir une instruction.', type: 'warning' });
                        return;
                    }

                    api.block('L\'IA Magix prépare votre contenu...');

                    fetch('/admin/index.php?controller=geminiai&action=generate', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            prompt: data.prompt,
                            context: selectedText,
                            tone: data.tone,
                            length: data.length,
                            action_type: data.action_type, // Ajouté
                            language: data.language      // Ajouté
                        })
                    })
                        .then(res => res.json())
                        .then(resData => {
                            api.unblock();
                            if (resData.content) {
                                api.setData({ result: resData.content });

                                // On n'active le bouton d'insertion que si ce n'est pas un message d'erreur
                                if (!resData.content.startsWith('Erreur :')) {
                                    api.setEnabled('insert_btn', true);
                                }
                            } else {
                                const errorMsg = resData.error || 'Erreur inconnue';
                                editor.notificationManager.open({ text: errorMsg, type: 'error' });
                            }
                        })
                        .catch(err => {
                            api.unblock();
                            editor.notificationManager.open({ text: 'Erreur de connexion avec le serveur MagixCMS.', type: 'error' });
                        });
                }
            },
            onSubmit: function(api) {
                const data = api.getData();
                if (data.result) {
                    // On insère le contenu et on crée un point de restauration (Undo)
                    editor.undoManager.transact(function () {
                        editor.insertContent(data.result);
                    });
                    api.close();
                }
            }
        });
    };

    // 4. ENREGISTREMENT UI
    editor.ui.registry.addButton('mc_ai_gemini', {
        icon: 'gemini-icon',
        tooltip: 'Assistant IA Magix',
        onAction: openAiDialog
    });

    editor.ui.registry.addMenuItem('mc_ai_gemini', {
        text: 'Assistant IA Magix',
        icon: 'gemini-icon',
        onAction: openAiDialog
    });

});