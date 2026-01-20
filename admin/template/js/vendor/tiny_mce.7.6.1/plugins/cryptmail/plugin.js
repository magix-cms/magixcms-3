/**
 * cryptmail for tinyMCE
 * Version 2.0.0 - Compatible TinyMCE 7
 * Developed by Gerits Aurélien - Magix CMS
 */
(function () {
    'use strict';
    tinymce.PluginManager.requireLangPack("cryptmail");
    tinymce.PluginManager.add('cryptmail', function (editor, url) {

        const _ = (text) => editor.translate(text);

        // --- FONCTIONS DE CONVERSION ---
        const bin2hex = (s) => {
            let a = [];
            for (let i = 0; i < s.length; i++) {
                a[i] = '%' + s.charCodeAt(i).toString(16).padStart(2, '0');
            }
            return a.join('');
        };

        const hex2bin = (hex) => {
            return decodeURIComponent(hex);
        };

        const toggleCrypt = () => {
            // On cherche le lien mailto: le plus proche du curseur
            const linkEl = editor.dom.getParent(editor.selection.getNode(), 'a[href^="mailto:"]');

            if (linkEl) {
                let href = editor.dom.getAttrib(linkEl, 'href');
                let address = href.replace('mailto:', '');
                let newAddress = '';

                // LOGIQUE : Si l'adresse contient déjà un %, on décrypte. Sinon on crypte.
                if (address.includes('%')) {
                    newAddress = hex2bin(address);
                } else {
                    for (let i = 0; i < address.length; i++) {
                        let char = address.charAt(i);
                        // On crypte tout sauf @ et . pour que l'email reste reconnaissable dans le code source si besoin
                        // ou on crypte tout (votre choix). Ici on crypte les lettres/chiffres :
                        newAddress += char.match(/[a-zA-Z0-9]/u) ? bin2hex(char) : char;
                    }
                }

                const newHref = 'mailto:' + newAddress;

                // ACTION : On modifie l'attribut directement
                editor.dom.setAttrib(linkEl, 'href', newHref);

                // CRUCIAL pour TinyMCE : On force la mise à jour du lien interne
                editor.undoManager.add();
                editor.nodeChanged();

                // Notification pour confirmer que l'action a eu lieu
                editor.notificationManager.open({
                    text: address.includes('%') ? 'Email décrypté' : 'Email crypté',
                    type: 'success',
                    timeout: 1500
                });
            } else {
                alert(_("Veuillez sélectionner un lien email (mailto)"));
            }
        };

        // --- UI REGISTRY ---
        editor.ui.registry.addIcon('cryptmail', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>');

        editor.ui.registry.addButton('cryptmail', {
            icon: 'cryptmail',
            tooltip: 'Crypter/Décrypter e-mail',
            onAction: toggleCrypt
        });

        editor.ui.registry.addMenuItem('cryptmail', {
            icon: 'cryptmail',
            text: 'Crypter/Décrypter e-mail',
            onAction: toggleCrypt
        });
    });
})();