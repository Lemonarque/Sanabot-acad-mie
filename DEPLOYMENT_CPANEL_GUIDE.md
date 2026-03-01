# Déploiement en production sur cPanel (Laravel 12)

Ce guide est adapté à ce projet (Livewire, Vite, Fedapay, files upload, queue DB).

## 1) Pré-requis cPanel

- PHP 8.2+ activé
- Extensions PHP: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`, `gd`
- Base MySQL créée (DB + user + mot de passe)
- SSL actif sur le domaine
- Idéalement: accès SSH (fortement recommandé)

## 2) Préparer le code

Option A (recommandée): déploiement via Git sur cPanel.

Option B: uploader une archive du projet via File Manager.

⚠️ Ne pas uploader:
- `node_modules`
- `.env` local
- fichiers temporaires

## 3) Build front (Vite)

Fais le build en local puis upload `public/build`:

```bash
npm install
npm run build
```

## 4) Installer dépendances backend

Si SSH dispo:

```bash
composer install --no-dev --optimize-autoloader
```

## 5) Configurer `.env` production

Crée `.env` depuis `.env.production.example` puis renseigne les vraies valeurs.

Variables clés:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://ton-domaine.com`
- DB MySQL
- Fedapay (`FEDAPAY_PUBLIC_KEY`, `FEDAPAY_SECRET_KEY`, `FEDAPAY_ENVIRONMENT=live`)

## 6) Document root

### Cas 1 (idéal): le domaine pointe directement vers `public/`
Rien à changer.

### Cas 2 (fréquent): `public_html/` imposé
- Mets le code Laravel dans un dossier parent (ex: `/home/user/sanabot_app`)
- Copie le contenu de `public/` vers `public_html/`
- Ajuste `public_html/index.php`:

```php
require __DIR__.'/../sanabot_app/vendor/autoload.php';
$app = require_once __DIR__.'/../sanabot_app/bootstrap/app.php';
```

(Adapte le chemin exact selon ton compte cPanel.)

## 7) Initialisation Laravel

Avec SSH:

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 8) Tâches planifiées / files d’attente

Ce projet utilise `QUEUE_CONNECTION=database`.

Comme cPanel n’a souvent pas de process long `queue:work` permanent, ajoute des CRON jobs:

### Cron 1 (scheduler Laravel) – chaque minute
```bash
* * * * * /usr/local/bin/php /home/USER/sanabot_app/artisan schedule:run >> /dev/null 2>&1
```

### Cron 2 (traitement queue) – chaque minute
```bash
* * * * * /usr/local/bin/php /home/USER/sanabot_app/artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
```

(Adapte `USER`, chemin projet et chemin PHP selon ton hébergeur.)

## 9) Permissions

Vérifie que Laravel peut écrire dans:
- `storage/`
- `bootstrap/cache/`

## 10) Fedapay en production

- Dans `.env`: `FEDAPAY_ENVIRONMENT=live`
- Configure la webhook côté Fedapay:

```text
https://ton-domaine.com/webhook/fedapay
```

- Vérifie `APP_URL` en HTTPS exact.

## 11) Checklist post-déploiement

- Connexion / inscription OK
- Catalogue / cours OK
- Upload image cours OK
- Institution dashboard + pagination OK
- Paiement Fedapay sandbox/live selon environnement
- Webhook paiement met bien à jour le statut

## 12) Commandes utiles maintenance

```bash
php artisan optimize:clear
php artisan migrate --force
php artisan queue:retry all
php artisan queue:failed
```

---

Si tu veux, je peux te préparer la variante « sans SSH » (100% File Manager cPanel) avec la procédure exacte clic par clic.
