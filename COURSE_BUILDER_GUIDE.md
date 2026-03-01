# Course Builder - Guide de Utilisation

## 📚 Vue d'ensemble

Le **Course Builder** est un outil complet pour gérer votre formation. Il unifie la création et la gestion du cours, des sections (modules), et offre une vue globale de votre structure pédagogique.

## 🎯 Avantages par rapport à l'ancienne méthode

| Aspect | Avant | Après |
|--------|-------|-------|
| Nombre de pages | 3+ pages | 1 seule page |
| Vue d'ensemble | ❌ Pas visible | ✅ Complète en temps réel |
| Paramètres du cours | 3 paramètres | 8+ paramètres |
| Catégories | ❌ Non gérées | ✅ Sélectionnables |
| Statistiques | ❌ Aucune | ✅ Complètes |
| Réorganisation | ❌ Non possible | ✅ Drag & réordonner |
| Progression | ❌ Manque d'info | ✅ Barre de complétion |

## 🚀 Accès au Course Builder

**Routes disponibles :**
- Créer un nouveau cours : `/formateur/cours/builder`
- Éditer un cours existant : `/formateur/cours/{courseId}/builder`

**Navigation :**
1. Accédez au panneau formateur
2. Cliquez sur "Mes Formations"
3. Cliquez sur "➕ Créer un nouveau cours" OU sur "✏️ Gérer" pour un cours existant

## 📝 Workflow Complet

### Étape 1: Configurer le Cours

Dans le **panneau gauche**, entrez les informations du cours :

- **Titre** : Nom du cours (obligatoire)
- **Description** : Résumé détaillé du cours (obligatoire)
- **Objectifs** : Objectifs pédagogiques
- **Catégorie** : Sélectionnez une catégorie parmi les disponibles
- **Niveau** : Débutant, Intermédiaire, Avancé
- **Langue** : Code langage (fr, en, es, etc.)
- **Durée totale** : Durée estimée en minutes
- **Public cible** : Description de l'audience cible

**Important :** Cliquez sur "✎ Éditer" pour modifier les paramètres

### Étape 2: Créer les Sections

Dans le **panneau droit**, une fois le cours créé :

1. Remplissez "Titre de la section"
2. Entrez la "Description de la section"
3. Cliquez sur "Créer section"

Les sections apparaissent immédiatement dans la liste.

### Étape 3: Ajouter des Leçons

Pour chaque section :

1. Cliquez sur le bouton "Gérer leçons"
2. Accédez à l'interface de gestion des leçons
3. Ajoutez les leçons avec ressources et quiz

#### Structure complète :
```
Cours
├── Section 1
│   ├── Leçon 1
│   │   ├── Ressource 1
│   │   ├── Ressource 2
│   │   └── Quiz
│   └── Leçon 2
├── Section 2
│   └── Leçon 3
└── Section 3
```

## 🎨 Indicateurs et Feedback

### Barre de Progression
Montre votre avancement dans la création du cours (4 étapes) :
1. ✓ Titre
2. ✓ Description
3. ✓ Sections (au moins 1)
4. ✓ Leçons (au moins 1 par section)

### Cartes de Statistiques
- **Sections** : Nombre de modules/sections
- **Leçons** : Total de leçons créées
- **Quiz** : Nombre de quiz associés
- **Ressources** : Total de ressources (documents, vidéos, etc.)

## 🔧 Actions sur les Sections

### Éditer
1. Cliquez sur l'icône "✎ Éditer"
2. Modifiez le titre et la description
3. Cliquez "✓ Enregistrer"

### Réorganiser
- **↑ Flèche vers le haut** : Déplacer la section plus haut
- **↓ Flèche vers le bas** : Déplacer la section plus bas

### Supprimer
1. Cliquez "🗑 Supprimer"
2. Confirmez la suppression (les leçons seront aussi supprimées)

## 💡 Conseils d'utilisation

### Bonnes pratiques

✅ **À faire :**
- Créer d'abord le cours complet avec ses paramètres
- Structurer avec des sections logiques
- Ajouter des sections avant de créer du contenu
- Utiliser des titres clairs et descriptifs
- Sauvegarder régulièrement les modifications
- Valider votre cours avant publication

❌ **À éviter :**
- Laisser le cours incomplet (titres/descriptions manquants)
- Sections vides sans leçons
- Leçons sans ressources pédagogiques
- Ne pas vérifier les statistiques

### Ordre recommandé
1. Créer le cours avec tous ses paramètres ✓
2. Ajouter 2-3 sections principales
3. Pour chaque section, ajouter 2-3 leçons
4. Remplir chaque leçon avec ressources
5. Ajouter des quiz pour évaluer
6. Valider et publier

## 📊 Comprendre les Statistiques

### Exemple de cours complet

```
📊 Statistiques du cours
├─ 3 Sections
├─ 8 Leçons
├─ 3 Quiz
├─ 12 Ressources
└─ 100% Complété
```

## 🔒 Sécurité et Propriété

- Vous ne pouvez éditer que **vos propres cours**
- Les erreurs d'accès sont bloquées automatiquement
- Utilisez "Supprimer" avec prudence (action irréversible)

## 🆘 Problèmes Courants

### Je ne peux pas ajouter de sections
**Solution :** Créez d'abord le cours en cliquant "Valider" dans le panneau gauche

### Mes modifications ne s'affichent pas
**Solution :** Attendez quelques secondes, la page se met à jour en temps réel

### Je veux annuler mes modifications
**Solution :** Cliquez "Annuler" ou quittez la page sans enregistrer

### La section ne se supprime pas
**Solution :** Confirmez la suppression dans la pop-up de confirmation

## 📝 Exemple Complet

Créons un cours de "JavaScript Débutant" :

1. **Paramètres :**
   - Titre : "JavaScript Débutant - Les Bases"
   - Description : "Apprenez les fondamentaux de JavaScript"
   - Niveau : Débutant
   - Catégorie : Programmation
   - Durée : 480 minutes (8 heures)

2. **Sections :**
   - Section 1 : "Introduc tion à JavaScript"
   - Section 2 : "Variables et Types de Données"
   - Section 3 : "Structures de Contrôle"
   - Section 4 : "Les Fonctions"

3. **Leçons (exemple Section 1) :**
   - Leçon 1 : "Qu'est-ce que JavaScript"
   - Leçon 2 : "Configuration de l'environnement"
   - Leçon 3 : "Votre premier script"

4. **Pour chaque Leçon :**
   - 1-2 ressources pédagogiques
   - 1 quiz de validation

## 🚀 Prochaines Étapes

Après avoir créé votre cours :

1. Demandez la validation auprès de l'admin
2. Publiez le cours une fois validé
3. Vérifiez les inscriptions
4. Consultez les statistiques d'apprentissage

---

**Course Builder** - Rendre simple la création de formations complètes ! 🎓
