# Quiz Builder - Guide de Utilisation

## 📋 Vue d'ensemble

Le **Quiz Builder** est un nouvel outil qui simplifie drastiquement la création et la gestion des quiz. Contrairement à l'ancienne méthode qui nécessitait de naviguer entre 3 pages différentes (Quiz → Questions → Réponses), le Quiz Builder réunit tout sur une seule interface intuitive.

## 🎯 Avantages

- ✅ **Une seule interface** : Gérer le quiz, les questions et les réponses au même endroit
- ✅ **Plus rapide** : Pas de rechargement de page entre les étapes
- ✅ **Design moderne** : Interface élégante avec glassmorphism
- ✅ **Feedback immédiat** : Messages de succès en temps réel
- ✅ **Gestion complète** : Créer, éditer, supprimer questions et réponses facilement
- ✅ **Sélection visuelle** : Marquer les bonnes réponses d'un simple clic

## 🚀 Comment accéder au Quiz Builder ?

1. En tant que **formateur**, naviguer vers : `Formateur > Cours > Sélectionner un cours > Modules > Leçon > Quiz`
2. Dans la section "Questions du quiz", cliquer sur le bouton bleu **"Quiz Builder"**

**Route directe :** `/formateur/lessons/{lessonId}/quiz/builder`

## 📝 Utilisation

### Étape 1 : Créer/Configurer le Quiz

1. Dans le **panneau gauche** ("Paramètres du quiz"), entrer :
   - **Titre du quiz** : Ex. "Quiz Routing Laravel"
   - **Score minimum (%)** : Score minimum pour réussir (0-100)
   - **Nombre d'essais maximum** : Combien de fois l'utilisateur peut tenter

2. Cliquer sur le bouton **"Valider"** pour créer ou mettre à jour le quiz

### Étape 2 : Ajouter des questions

1. Une fois le quiz créé, une section **"Ajouter une question"** apparaît
2. Entrer le texte de la question dans le champ
3. Cliquer sur **"+ Ajouter question"**
4. La question apparaît maintenant à droite

### Étape 3 : Ajouter des réponses

1. Cliquer sur une question pour l'éditer
2. En bas, une section **"Ajouter réponse"** s'affiche
3. Entrer le texte de la réponse
4. **Cocher "Correcte"** si c'est une bonne réponse
5. Cliquer **"+ Ajouter réponse"**

### ✎️ Éditer

- **Questions** : Cliquer sur l'icône ✎ → Modifier le texte → Cliquer ✓
- **Réponses** : Modifier directement dans le champ ou cliquer sur la checkbox verte

### 🗑️ Supprimer

- **Questions** : Cliquer sur l'icône 🗑 en haut à droite
- **Réponses** : Cliquer sur le bouton 🗑 qui apparaît au survol

## 🎨 Interface détaillée

### Panneau Gauche
```
┌──────────────────────────┐
│  Paramètres du quiz      │
├──────────────────────────┤
│ Titre du quiz            │
│ [____________]           │
│                          │
│ Score minimum (%)        │
│ [___]                    │
│                          │
│ Nombre d'essais max      │
│ [___]                    │
│                          │
│ [ Valider ] [ Supprimer ]│
│                          │
│ ✓ Quiz créé - X qu.     │
└──────────────────────────┘
```

### Panneau Droit
```
┌────────────────────────────┐
│ Ajouter une question       │
├────────────────────────────┤
│ [_________________]        │
│     (textarea)             │
│ [ + Ajouter question ]     │
└────────────────────────────┘

┌────────────────────────────┐
│ Question 1 - 3 réponses    │
├────────────────────────────┤
│ Texte de la question...    │
│ [ ✎ ] [ 🗑 ]               │
│                            │
│ Réponses :                 │
│ [✓] Réponse 1 [ X ]        │
│ [ ] Réponse 2 [ X ]        │
│ [ ] Réponse 3 [ X ]        │
└────────────────────────────┘
```

## 🔄 Comparaison : Avant vs Après

### Avant (3 pages)
1. Aller à Quiz > Créer/Éditer Quiz
2. Aller à Quiz > Questions > Ajouter Question
3. Aller à Quiz > Questions > Réponses > Ajouter Réponse
4. Répéter pour chaque question

**Temps estimé** : ~2-3 minutes par question

### Après (1 page)
1. Accéder à Quiz Builder
2. Configurer le quiz
3. Ajouter toutes les questions et réponses
4. Terminer

**Temps estimé** : ~1-2 minutes pour 5 questions

## 💡 Conseils

- Créer d'abord le quiz avec tous ses paramètres
- Ajouter les questions, puis éditer chacune pour ajouter les réponses
- Au minimum **une réponse doit être correcte** par question
- Les bonnes réponses sont marquées avec une checkbox ✓ verte
- On peut éditer les questions et réponses directement dans les champs

## 🛠️ Maintenance

Si vous avez besoin de l'ancienne interface (pages séparées), elle reste disponible via:
- `/formateur/quiz/{quizId}/questions` → Gestion des questions
- `/formateur/quiz/questions/{questionId}/answers` → Gestion des réponses

## 📞 Support

Pour toute question ou problème avec le Quiz Builder, vérifiez :
- Que le quiz est bien créé avant d'ajouter des questions
- Que toutes les réponses sont marquées comme correctes ou incorrectes
- Les messages d'erreur en haut de la page pour plus de détails

---

**Quiz Builder** - Créé pour simplifier votre expérience de création de quiz ! 🎓
