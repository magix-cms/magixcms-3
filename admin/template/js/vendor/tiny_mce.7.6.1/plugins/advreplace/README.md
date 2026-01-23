# Plugin : advreplace pour tinyMCE

![Version TinyMCE](https://img.shields.io/badge/TinyMCE-6%20%7C%207-blue)
![Licence](https://img.shields.io/badge/licence-MIT-green)
![Statut](https://img.shields.io/badge/projet-Open%20Source-orange)
![Langue](https://img.shields.io/badge/langue-FR%20%7C%20EN-brightgreen)

**Nom technique :** `advreplace`  
**Type :** Utilitaire TinyMCE (v6 & v7)  
**Fonction :** Recherche et remplacement par Regex dans le code source HTML.

## Description
**advreplace** est un outil puissant permettant d'effectuer des modifications chirurgicales dans le code HTML généré par l'éditeur, sans obliger l'utilisateur à basculer manuellement en vue "Code Source". Il est capable de nettoyer des attributs invisibles, de reformater des balises et de corriger des liens en masse.

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

## Sécurité et Annulation

* **Mode Interne :** Ce plugin travaille uniquement sur le contenu chargé dans l'éditeur. Il ne modifie rien sur le serveur tant que vous n'avez pas enregistré la page.
* **Historique (Undo) :** En cas d'erreur de manipulation ou si le résultat de la Regex ne convient pas, utilisez simplement le raccourci **Ctrl+Z** (ou le bouton Annuler) pour revenir instantanément à l'état précédent.

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