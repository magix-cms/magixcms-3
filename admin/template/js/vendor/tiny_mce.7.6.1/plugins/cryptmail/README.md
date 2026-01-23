# TinyMCE Cryptmail Plugin

A lightweight and secure plugin for **TinyMCE 6 & 7** designed to protect email addresses from spam bots by encrypting `mailto:` links into hexadecimal strings.
Developed by **Gerits Aurélien**.

![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
![Licence](https://img.shields.io/badge/licence-MIT-green)
![Statut](https://img.shields.io/badge/projet-Open%20Source-orange)

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
Ce projet est sous licence MIT.
Développé par Gerits Aurélien.
Copyright (c) 2026, Magix CMS.

MIT License

Copyright (c) 2026 Gerits Aurélien (Magix CMS)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
