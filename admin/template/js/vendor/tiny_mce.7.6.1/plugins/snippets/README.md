# Snippet Manager Pro for TinyMCE (6 & 7)

**Snippet Manager Pro** est une extension Open Source pour TinyMCE qui permet d'insérer des modèles HTML dynamiques. 
Il inclut un moteur de recherche en temps réel et une prévisualisation isolée via Iframe.

![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
![Statut](https://img.shields.io/badge/projet-Open%20Source-orange)

## Soutenir le projet

Si vous souhaitez soutenir le développement, vous pouvez faire un don via PayPal :

[![Faire un don](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?business=BQBYN3XYGMDML&no_recurring=0&currency_code=EUR)

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

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS)
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation ; soit la version 3 de la Licence, ou (à votre discrétion) toute version ultérieure.

---
