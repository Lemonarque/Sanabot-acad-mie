# Configuration du paiement avec Fedapay

## 📋 Vue d'ensemble

Sanabot Académie utilise **Fedapay** comme solution de paiement pour l'Afrique de l'Ouest. Fedapay permet d'accepter les paiements via:

- 📱 **Mobile Money**: Orange Money, MTN Mobile Money, Moov Money, Wave
- 💳 **Cartes bancaires**: Visa, Mastercard
- 💵 **Devises**: XOF (Franc CFA), XAF, et autres

## 🚀 Configuration initiale

### 1. Créer un compte Fedapay

1. Rendez-vous sur [https://fedapay.com](https://fedapay.com)
2. Créez un compte marchand
3. Complétez le processus de vérification KYC (Know Your Customer)
4. Récupérez vos clés API dans le dashboard Fedapay

### 2. Configurer les variables d'environnement

Ajoutez ces lignes dans votre fichier `.env`:

```env
# Configuration Fedapay
FEDAPAY_PUBLIC_KEY=pk_sandbox_your_public_key
FEDAPAY_SECRET_KEY=sk_sandbox_your_secret_key
FEDAPAY_ENVIRONMENT=sandbox
FEDAPAY_WEBHOOK_SECRET=your_webhook_secret
```

**Notes importantes:**
- En mode `sandbox`: utilisez les clés de test (préfixées par `pk_sandbox_` et `sk_sandbox_`)
- En mode `live` (production): utilisez les clés de production (préfixées par `pk_live_` et `sk_live_`)
- Le `FEDAPAY_WEBHOOK_SECRET` est généré dans votre dashboard Fedapay (section Webhooks)

### 3. Configurer les webhooks Fedapay

Les webhooks permettent à Fedapay de notifier votre application lorsqu'un paiement est confirmé.

1. Connectez-vous à votre dashboard Fedapay
2. Allez dans **Paramètres > Webhooks**
3. Ajoutez l'URL de webhook: `https://votre-domaine.com/webhook/fedapay`
4. Copiez le **Webhook Secret** généré
5. Ajoutez-le dans votre `.env` sous `FEDAPAY_WEBHOOK_SECRET`
6. Sélectionnez l'événement à écouter: **`transaction.approved`**

## 💰 Fonctionnalités implémentées

### Pour les formateurs

1. **Activer la tarification d'un cours**:
   - Dans le builder de cours, cochez "Ce cours est payant"
   - Définissez le prix (minimum 100 XOF)
   - Les apprenants devront payer avant de s'inscrire

2. **Leçons de prévisualisation gratuites**:
   - Dans l'éditeur de leçon, cochez "Leçon de prévisualisation gratuite"
   - Ces leçons sont accessibles même sans inscription

### Pour les apprenants

1. **Consultation des cours**:
   - Les cours gratuits peuvent être suivis immédiatement
   - Les cours payants affichent le prix et un bouton "Payer"

2. **Processus de paiement**:
   - Cliquez sur "Payer [PRIX] XOF"
   - Choisissez votre méthode de paiement (Mobile Money ou Carte bancaire)
   - Complétez le paiement sur la page sécurisée Fedapay
   - Inscription automatique après confirmation du paiement

3. **Confirmation**:
   - Redirection automatique vers la page de succès
   - Email de confirmation envoyé
   - Accès immédiat au cours

### Pour les administrateurs

1. **Gestion des paiements**:
   - Tableau de bord avec statistiques financières
   - Liste des paiements dans **Admin > Paiements**
   - Statuts: `pending`, `completed`, `cancelled`, `failed`

2. **Métriques financières**:
   - Revenus totaux
   - Revenus du mois en cours
   - Nombre de cours payants
   - Nombre de transactions

## 🔒 Sécurité

### Vérification des webhooks

Le système vérifie automatiquement la signature des webhooks Fedapay pour éviter les requêtes frauduleuses:

```php
$signature = $request->header('X-FedaPay-Signature');
$expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

if ($signature !== $expectedSignature) {
    // Webhook rejeté
}
```

### Protection des données

- Les informations bancaires ne transitent jamais par notre serveur
- Tous les paiements sont traités directement par Fedapay (certifié PCI-DSS)
- Les clés API secrètes ne doivent JAMAIS être exposées côté client

## 🧪 Tests en mode Sandbox

### Cartes de test Fedapay

En mode sandbox, utilisez ces cartes de test:

| Numéro de carte | Résultat |
|-----------------|----------|
| `4000 0000 0000 0002` | Paiement réussi |
| `4000 0000 0000 0010` | Paiement refusé |
| `4000 0000 0000 0028` | Carte invalide |

- **CVV**: n'importe quel code à 3 chiffres
- **Date d'expiration**: n'importe quelle date future

### Numéros Mobile Money de test

Utilisez ces numéros pour tester Mobile Money en sandbox:

- Orange Money: `+229 XX XX XX XX` (n'importe quel numéro au format béninois)
- MTN: `+229 XX XX XX XX`
- Moov: `+229 XX XX XX XX`

**Note**: En mode sandbox, aucun débit réel n'est effectué.

## 📊 Structure de la base de données

### Table `payments`

```sql
- id (bigint)
- user_id (foreign key → users)
- course_id (foreign key → courses)
- amount (decimal)
- currency (string) - défaut: XOF
- status (enum: pending, completed, cancelled, failed)
- transaction_id (string) - ID de transaction Fedapay
- payment_method (string) - mobile_money, card
- created_at, updated_at
```

## 🔄 Flux de paiement

```
1. Apprenant clique "Payer" sur un cours payant
   ↓
2. Redirection vers /cours/{id}/checkout
   ↓
3. Sélection de la méthode de paiement
   ↓
4. Création d'un enregistrement Payment (status: pending)
   ↓
5. Création d'une transaction Fedapay via API
   ↓
6. Redirection vers la page de paiement Fedapay
   ↓
7. Apprenant effectue le paiement
   ↓
8. Fedapay envoie un webhook "transaction.approved"
   ↓
9. Notre serveur vérifie et traite le webhook
   ↓
10. Mise à jour Payment (status: completed)
    ↓
11. Création automatique de l'inscription (Enrollment)
    ↓
12. Redirection vers page de succès
```

## 🛠 Commandes utiles

### Vérifier les paiements en attente

```bash
php artisan tinker
>>> App\Models\Payment::where('status', 'pending')->get();
```

### Tester la connexion à Fedapay

```bash
php artisan tinker
>>> FedaPay\FedaPay::setApiKey(config('services.fedapay.secret_key'));
>>> FedaPay\FedaPay::setEnvironment('sandbox');
>>> $transactions = FedaPay\Transaction::all();
>>> $transactions;
```

## 📞 Support

### Fedapay

- Documentation: [https://docs.fedapay.com](https://docs.fedapay.com)
- Support: [support@fedapay.com](mailto:support@fedapay.com)
- Dashboard: [https://dashboard.fedapay.com](https://dashboard.fedapay.com)

### Problèmes courants

**Webhook non reçu:**
- Vérifiez que l'URL du webhook est correcte et accessible publiquement
- Vérifiez les logs dans le dashboard Fedapay (section Webhooks > Logs)
- En développement local, utilisez ngrok ou expose.dev pour exposer votre serveur

**Paiement bloqué en "pending":**
- Vérifiez que le webhook est correctement configuré
- Vérifiez les logs Laravel: `storage/logs/laravel.log`
- Vérifiez le statut dans le dashboard Fedapay

**Erreur d'API:**
- Vérifiez que les clés API sont correctes
- Vérifiez que l'environnement (sandbox/live) correspond aux clés utilisées
- Consultez les logs pour plus de détails

## 🚀 Passage en production

Avant de passer en production:

1. ✅ Complétez votre vérification KYC sur Fedapay
2. ✅ Obtenez vos clés de production (`pk_live_*` et `sk_live_*`)
3. ✅ Mettez à jour `.env` avec `FEDAPAY_ENVIRONMENT=live`
4. ✅ Configurez le webhook de production
5. ✅ Testez un paiement réel avec un petit montant
6. ✅ Vérifiez que tous les webhooks sont reçus correctement

## 📝 Recommandations

- **Stockez les logs de paiement**: Activez le logging pour tous les événements de paiement
- **Notifications email**: Envoyez des confirmations par email après chaque paiement
- **Remboursements**: Gérez les demandes de remboursement via le dashboard Fedapay
- **Rapports financiers**: Exportez régulièrement les rapports de transactions
- **Monitoring**: Surveillez les taux de succès des paiements

---

**Système de paiement créé le**: 12 février 2026  
**Version de Fedapay**: PHP SDK 0.4.7  
**Dernière mise à jour**: 12 février 2026
