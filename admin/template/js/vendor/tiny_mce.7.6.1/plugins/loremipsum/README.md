# Lorem Ipsum Pro for TinyMCE (6 & 7)

**Lorem Ipsum Pro** est un plugin pour TinyMCE permettant de générer instantanément des paragraphes de texte factice. La version 2.0 introduit un générateur dynamique permettant de choisir la quantité de texte à insérer.

## Version & Compatibilité

* **Version 2.x** (Actuelle) : Compatible avec **TinyMCE 6** et **TinyMCE 7**.
* **Version 1.x** : Compatible avec **TinyMCE 5**.

[![release](https://img.shields.io/github/release/gtraxx/tinymce-lorem-ipsum.svg)](https://github.com/gtraxx/tinymce-lorem-ipsum/releases)
![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
![Licence](https://img.shields.io/badge/licence-MIT-green)
![Statut](https://img.shields.io/badge/projet-Open%20Source-orange)

> [!IMPORTANT]
> Si vous utilisez encore **TinyMCE 5**, veuillez télécharger la **Version 1.0** disponible dans les [Releases GitHub](https://github.com/gtraxx/tinymce-lorem-ipsum/releases/tag/v1.0.0).

---

## Fonctionnalités

* **Générateur Dynamique** : Choisissez précisément entre 1 et 20 paragraphes via une liste déroulante pour un remplissage rapide et précis.
* **Support I18n** : Entièrement traduisible (Anglais par défaut).
* **Code Moderne** : Optimisé pour TinyMCE 7 (API Registry & Dialog).
* **Léger** : Aucune dépendance externe, icône SVG intégrée.

## Installation

1. Téléchargez l'archive.
2. Décompressez l'archive dans le répertoire des plugins de TinyMCE (ex: `tiny_mce/plugins/loremipsum`).
3. Initialisez le plugin dans votre configuration JavaScript :

```javascript
tinymce.init({
    selector: "textarea",
    plugins: "loremipsum ...",
    toolbar: "undo redo | styles | bold italic | loremipsum",
});
```

## Traduction (I18n)
Le plugin est écrit en anglais par défaut pour une compatibilité universelle. Pour l'utiliser en français, ajoutez ces clés à votre fichier langs/fr_FR.js :

```javascript
tinymce.addI18n('fr_FR', {
    "Lorem Ipsum Generator": "Générateur Lorem Ipsum",
    "Number of paragraphs": "Nombre de paragraphes",
    "Generate Lorem Ipsum": "Générer du Lorem Ipsum",
    "Lorem Ipsum Generator...": "Générateur Lorem Ipsum...",
    "Insert": "Insérer",
    "Cancel": "Annuler"
});
```

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