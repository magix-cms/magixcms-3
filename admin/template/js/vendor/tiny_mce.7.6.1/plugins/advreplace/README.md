# Plugin : advreplace pour tinyMCE

[![release](https://img.shields.io/github/release/gtraxx/tinymce-advreplace.svg)](https://github.com/gtraxx/tinymce-advreplace/releases/latest)
![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](http://www.gnu.org/licenses/gpl-3.0)
![Statut](https://img.shields.io/badge/projet-Open%20Source-orange)
![Langue](https://img.shields.io/badge/langue-FR%20%7C%20EN-brightgreen)

**Nom technique :** `advreplace`  
**Type :** Utilitaire TinyMCE (v6 & v7)  
**Fonction :** Recherche et remplacement par Regex dans le code source HTML.

## Description
**advreplace** est un outil puissant permettant d'effectuer des modifications chirurgicales dans le code HTML généré par l'éditeur, sans obliger l'utilisateur à basculer manuellement en vue "Code Source". Il est capable de nettoyer des attributs invisibles, de reformater des balises et de corriger des liens en masse.

## Soutenir le projet

Si vous souhaitez soutenir le développement, vous pouvez faire un don via PayPal :

[![Faire un don](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?business=BQBYN3XYGMDML&no_recurring=0&currency_code=EUR)

## Structure du dossier
Le dossier `plugins/advreplace` doit contenir les fichiers suivants pour fonctionner :
* `plugin.js` (Cœur du plugin)
* `formulas.json` (Votre bibliothèque de règles)
* `langs/fr_FR.js` (Fichier de traduction)

### Initialization
Add the plugin to your TinyMCE setup. It is highly recommended to also include the native `code` plugin to enable the auto-verification feature.

```javascript
tinymce.init({
    selector: '#editor',

    // 1. Register the plugin
    plugins: 'code advreplace',

    // 2. Add the button to your toolbar
    toolbar: 'undo redo | advreplace code | bold italic',

    // 3. (Optional) Add to the menu bar
    menu: {
        tools: { title: 'Tools', items: 'advreplace code' }
    }
});
```
## Nouveautés de la v2.0

La version 2.0 transforme cet utilitaire en une véritable **station de nettoyage intelligente**, idéale pour traiter le contenu généré par IA (ChatGPT, Gemini) ou les copier-coller depuis Word.

* **Bibliothèque Externe (JSON)** : Plus besoin de toucher au code JS. Toutes vos formules Regex sont stockées dans `formulas.json`.
* **Recherche Dynamique** : Filtrez instantanément parmi des dizaines de règles (ex: tapez "SEO" ou "Gemini").
* **Auto-Remplissage** : La sélection d'un preset remplit automatiquement les champs de recherche et de remplacement.
* **Internationalisation** : Support natif des fichiers de langue (fr_FR inclus par défaut).

## Guide d'utilisation rapide

### 1. Accéder à l'outil
Cliquez sur l'icône **"Nettoyage avancé"** (une loupe sur des balises `< >`) dans la barre d'outils de l'éditeur.

### 2. Interface de configuration
Une fenêtre s'ouvre avec les options suivantes :

* **Chercher (Regex) :** Le motif de texte ou de code à trouver.
* **Remplacer par :** La valeur de remplacement (laisser vide pour supprimer).
* **Respecter la casse :** Cocher si la différence majuscule/minuscule est importante.
* **Vérifier le code source après application :** Ouvre automatiquement l'éditeur de code natif pour valider visuellement le nettoyage.

## Cas pratique : Nettoyage

Pour supprimer les attributs de structure (`path-to-node`, `index-in-node`) et leurs résidus potentiels (espaces, préfixes `data-`), utilisez la formule certifiée ci-dessous.

### Procédure de suppression totale :

1.  Dans le champ **Chercher (Regex)**, copiez exactement cette formule :
    ```regex
    \s+(data-)?(path-to-node|index-in-node)=["'][^"']*["']
    ```
2.  Laissez le champ **Remplacer par** totalement **VIDE**.
3.  Cochez **"Vérifier le code source après application"**.
4.  Cliquez sur **Appliquer**.

> **Note technique :** Cette formule supprime l'espace avant l'attribut (`\s+`), gère le préfixe optionnel (`data-`), cible vos attributs spécifiques et capture toute la valeur entre guillemets simples ou doubles.

## Explication de la formule :

* \s+ : Capture l'espace avant l'attribut (évite les doubles espaces div class).
* (data-)? : Capture le préfixe data- s'il a été ajouté automatiquement par le navigateur.
* (path|index...) : Cible vos attributs spécifiques.
* =["'][^"']*["'] : Capture la valeur, qu'elle soit entre guillemets simples ' ou doubles ".

## Autres commandes utiles

Voici une liste de formules Regex courantes pour la maintenance de contenu :

| Action désirée | Formule à chercher (Regex) | Remplacement |
| :--- | :--- | :--- |
| **Supprimer tous les styles en ligne** (Nettoyage CSS) | `\s+style=["'][^"']*["']` | *(Laisser vide)* |
| **Supprimer les IDs des balises** | `\s+id=["'][^"']*["']` | *(Laisser vide)* |
| **Supprimer les classes commençant par "tmp-"** | `\s+class=["']tmp-[^"']*["']` | *(Laisser vide)* |
| **Passer les liens HTTP en HTTPS** | `href="http://` | `href="https://` |
| **Corriger les chemins d'images absolus** | `src="http://monsite.com/img/` | `src="/img/` |

## Personnalisation (formulas.json)
Vous pouvez ajouter vos propres règles dans le fichier formulas.json à la racine du plugin.

### Format attendu :

```json
{
  "name": "Catégorie : Nom de l'action",
  "find": "Votre_Regex_Echappée",
  "replace": "Valeur_de_Remplacement"
}
```
⚠️ Important : En JSON, les backslashes doivent être doublés.

* Pour cibler un espace `\s`, écrivez `\\s`.
* Pour cibler une quote `"`, échappez-la `\"`.

## Sécurité et Annulation

* **Mode Interne :** Ce plugin travaille uniquement sur le contenu chargé dans l'éditeur. Il ne modifie rien sur le serveur tant que vous n'avez pas enregistré la page.
* **Historique (Undo) :** En cas d'erreur de manipulation ou si le résultat de la Regex ne convient pas, utilisez simplement le raccourci **Ctrl+Z** (ou le bouton Annuler) pour revenir instantanément à l'état précédent.

## Licence

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS)
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation ; soit la version 3 de la Licence, ou (à votre discrétion) toute version ultérieure.

---