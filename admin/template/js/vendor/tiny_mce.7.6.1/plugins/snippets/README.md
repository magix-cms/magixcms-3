# Snippet Manager Pro for TinyMCE (6 & 7)

**Snippet Manager Pro** est une extension Open Source pour TinyMCE qui permet d'insérer des modèles HTML dynamiques. 
Il inclut un moteur de recherche en temps réel et une prévisualisation isolée via Iframe.

## Fonctionnalités

- **Recherche Instantanée** : Filtrez vos modèles par titre ou description.
- **Preview Fidèle** : L'aperçu utilise vos propres fichiers CSS (`content_css`).
- **Accessibilité** : Entièrement pilotable au clavier.
- **Gestion des doublons** : Identification unique des modèles même s'ils portent le même nom.

## Raccourcis Clavier

Pour une productivité accrue, vous pouvez ouvrir la bibliothèque de modèles instantanément :

| Plateforme | Raccourci |
| :--- | :--- |
| **Windows / Linux** | `Ctrl` + `Maj` + `S` |
| **macOS** | `Cmd (⌘)` + `Maj (⇧)` + `S` |

## Installation

1. Copiez le dossier `snippets` dans le répertoire `plugins` de votre installation TinyMCE.
2. Initialisez le plugin dans votre configuration :

```javascript
tinymce.init({
    selector: '.mceEditor',
    plugins: 'snippets ...',
    toolbar: 'snippets | ...',
    // Obligatoire : URL retournant la liste des modèles au format JSON
    snippets_url: '/votre-api/get-snippets.php',
    // Requis pour l'aperçu fidèle
    content_css: '/css/votre-style.css',
    license_key: 'gpl'
});
```

## Format de réponse API (JSON)
Votre serveur doit retourner un tableau d'objets. Le champ description est optionnel mais recommandé pour faciliter la recherche.

## Configuration Serveur (Backend)
Votre point de terminaison (snippets_url) doit retourner un tableau JSON structuré comme suit :

```javascript
[
    {
        "id": 1,
        "title": "Grille 3 Colonnes",
        "description": "Mise en page Bootstrap 5",
        "url": "/path/to/html/snippet1.html"
    },
    {
        "id": 2,
        "title": "Appel à l'action (CTA)",
        "description": "Bouton centré avec fond coloré",
        "url": "/path/to/html/snippet2.html"
    }
]
```

Note : L'URL fournie pour chaque snippet doit retourner du HTML brut (sans balises <html> ou <body>).

<img width="1200" height="651" alt="Image" src="https://github.com/user-attachments/assets/1b58ef75-ffa6-44df-b027-deec662ffb35" />

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
