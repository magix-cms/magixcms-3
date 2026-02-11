# Lorem Ipsum Pro for TinyMCE (6 & 7)

**Lorem Ipsum Pro** est un plugin pour TinyMCE permettant de générer instantanément des paragraphes de texte factice. La version 2.0 introduit un générateur dynamique permettant de choisir la quantité de texte à insérer.

## Version & Compatibilité

* **Version 2.x** (Actuelle) : Compatible avec **TinyMCE 6** et **TinyMCE 7**.
* **Version 1.x** : Compatible avec **TinyMCE 5**.

[![release](https://img.shields.io/github/release/gtraxx/tinymce-lorem-ipsum.svg)](https://github.com/gtraxx/tinymce-lorem-ipsum/releases)
![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
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
<pre>
This file is part of tinyMCE.
YouTube for tinyMCE
Copyright (C) 2011 - 2025  Gerits Aurelien <aurelien[at]magix-cms[dot]com>

Redistributions of files must retain the above copyright notice.
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see .

####DISCLAIMER

Do not edit or add to this file if you wish to upgrade jimagine to newer
versions in the future. If you wish to customize jimagine for your
needs please refer to magix-dev.be for more information.
</pre>