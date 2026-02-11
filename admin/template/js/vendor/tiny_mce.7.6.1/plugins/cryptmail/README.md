# TinyMCE Cryptmail Plugin

A lightweight and secure plugin for **TinyMCE 6 & 7** designed to protect email addresses from spam bots by encrypting `mailto:` links into hexadecimal strings.
Developed by **Gerits Aurélien**.

![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
![Statut](https://img.shields.io/badge/projet-Open%20Source-orange)

## Soutenir le projet

Si vous souhaitez soutenir le développement, vous pouvez faire un don via PayPal :

[![Faire un don](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?business=BQBYN3XYGMDML&no_recurring=0&currency_code=EUR)

## Features

- **One-Click Encryption:** Instantly convert any `mailto:` link into an opaque hexadecimal string.
- **Toggle Mode:** Easily decrypt a link to edit the address and re-encrypt it afterwards.
- **Full Security:** Encodes the entire string (including `@` and `.`) to bypass advanced crawlers.
- **Translation Ready:** Fully compatible with TinyMCE's I18n API.
- **Modern UI:** Uses TinyMCE 7 native notifications and dialogs.

## Installation

### 1. Upload the plugin
Copy the `cryptmail` folder into your TinyMCE `plugins` directory.

### 2. Configure TinyMCE
Add `cryptmail` to your plugin list and toolbar configuration:

```javascript
tinymce.init({
    selector: 'textarea',
    plugins: 'link cryptmail',
    toolbar: 'link cryptmail',
    // Optional: add to the context menu
    contextmenu: 'link cryptmail',
});
```

## Localization

```javascript
tinymce.addI18n('fr_FR', {
    'Encrypt/Decrypt email': 'Crypter/Décrypter l\'e-mail',
    'Email decrypted': 'E-mail décrypté avec succès',
    'Email encrypted': 'E-mail protégé (crypté)',
    'Please select an email link (mailto)': 'Veuillez d\'abord sélectionner un lien e-mail (mailto)'
});
```
## Why use this?
Standard email crawlers scan HTML for the @ symbol or mailto: tags. By converting mailto:me@example.com into mailto:%6d%65%40%65%78%61%6d%70%6c%65%2e%63%6f%6d, 
the link remains perfectly functional for human users (the browser decodes it automatically upon clicking) but becomes invisible to most automated harvesters.

## Licence

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS)
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation ; soit la version 3 de la Licence, ou (à votre discrétion) toute version ultérieure.

---