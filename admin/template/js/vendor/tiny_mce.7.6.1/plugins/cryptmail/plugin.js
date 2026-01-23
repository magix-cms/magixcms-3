/**
 * cryptmail for tinyMCE
 * Version 2.1.0 - Compatible TinyMCE 7
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
                // Encodage systématique de chaque caractère
                a[i] = '%' + s.charCodeAt(i).toString(16).padStart(2, '0');
            }
            return a.join('');
        };

        const hex2bin = (hex) => {
            try {
                return decodeURIComponent(hex);
            } catch (e) {
                return hex; // Repli sécurisé si le décodage échoue
            }
        };

        const toggleCrypt = () => {
            const linkEl = editor.dom.getParent(editor.selection.getNode(), 'a[href^="mailto:"]');

            if (linkEl) {
                let href = editor.dom.getAttrib(linkEl, 'href');
                let address = href.replace('mailto:', '');
                let newAddress = '';

                // LOGIQUE RENFORCÉE :
                // Si l'adresse contient %, on considère qu'elle est cryptée -> on décrypte.
                // Sinon, on crypte l'intégralité (y compris @ et .)
                if (address.includes('%')) {
                    newAddress = hex2bin(address);
                } else {
                    newAddress = bin2hex(address); // Cryptage total
                }

                const newHref = 'mailto:' + newAddress;

                editor.dom.setAttrib(linkEl, 'href', newHref);

                // Mise à jour TinyMCE
                editor.undoManager.add();
                editor.nodeChanged();

                // Notification traduite
                editor.notificationManager.open({
                    text: address.includes('%') ? _('Email decrypted') : _('Email encrypted'),
                    type: 'success',
                    timeout: 1500
                });
            } else {
                // Alerte traduite
                editor.windowManager.alert(_("Please select an email link (mailto)"));
            }
        };

        // --- UI REGISTRY ---
        editor.ui.registry.addIcon('cryptmail', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M12 8v4"></path><path d="M12 16h.01"></path></svg>');

        editor.ui.registry.addButton('cryptmail', {
            icon: 'cryptmail',
            tooltip: _('Encrypt/Decrypt email'),
            onAction: toggleCrypt
        });

        editor.ui.registry.addMenuItem('cryptmail', {
            icon: 'cryptmail',
            text: _('Encrypt/Decrypt email'),
            onAction: toggleCrypt
        });
    });
})();