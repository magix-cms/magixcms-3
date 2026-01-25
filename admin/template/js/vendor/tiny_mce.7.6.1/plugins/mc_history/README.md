# Plugin TinyMCE : Revision History (mc_history)

Ce plugin ajoute une fonctionnalité de gestion de versions (versioning) à l'éditeur TinyMCE pour **Magix CMS**. Il permet de sauvegarder automatiquement l'historique des modifications et de restaurer des versions précédentes en cas d'erreur.

## Fonctionnalités Clés

* **Sauvegarde intelligente :** Une nouvelle version est créée uniquement si le contenu a été réellement modifié.
* **Nettoyage automatique :** Le système conserve uniquement les **10 dernières révisions** par page et par langue (rotation automatique).
* **Sécurité :** Les sauvegardes vides ou corrompues sont ignorées pour ne pas polluer l'historique.
* **Restauration fluide :** Remplace le contenu actuel par une ancienne version sans rechargement de page.
* **Support Annulation :** L'action de restauration est compatible avec la fonction "Annuler" (`Ctrl+Z`) de TinyMCE.

---

## Raccourcis Clavier

Accédez rapidement à l'historique grâce aux raccourcis natifs :

| Système d'exploitation | Raccourci | Action |
| :--- | :--- | :--- |
| **Windows / Linux** | `Ctrl` + `H` | Ouvrir la fenêtre d'historique |
| **macOS** | `Cmd` (⌘) + `H` | Ouvrir la fenêtre d'historique |

---

## Guide d'Utilisation

### 1. Accéder à l'outil
Le plugin est accessible via le menu **Outils** > **Historique des révisions**, ou via le raccourci clavier.

### 2. Restaurer une version
1.  Ouvrez la fenêtre d'historique. Une liste des sauvegardes apparaît, triée de la plus récente à la plus ancienne.
2.  Identifiez la version souhaitée grâce à la **date et l'heure exacte** (secondes incluses).
3.  Cliquez sur le bouton **Restaurer** correspondant.
4.  Confirmez l'action dans la fenêtre de dialogue.

> ** Astuce :** Si vous avez restauré une version par erreur, faites immédiatement `Ctrl+Z` (ou `Cmd+Z`) pour revenir à votre texte précédent.

---

## Intégration Technique (Développeurs)

Pour que le plugin fonctionne, le `textarea` TinyMCE doit posséder les attributs de données (`data-*`) correspondant au contexte de Magix CMS.

### Configuration du Textarea
```html
<textarea 
    class="mceEditor" 
    name="content[1][description]"
    data-controller="pages"      
    data-itemid="42"
    data-lang="1"                
    data-field="content_pages"   >
   ...
</textarea>
```
### Cas des modules à page unique (ex: Contact, Settings)
Si l'attribut `data-itemid` est omis ou vide sur le textarea, le plugin attribuera automatiquement la valeur **1** par défaut. Cela permet de centraliser l'historique pour les modules ne gérant qu'un seul enregistrement.

### Configuration TinyMCE

Ajoutez mc_history à votre configuration :

```javascript
tinymce.init({
    selector: '.mceEditor',
    plugins: 'mc_history code ...',
    toolbar: 'undo redo | mc_history | ...',
    menu: {
        tools: { title: 'Outils', items: 'mc_history code' }
    }
});
```