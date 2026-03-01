# Déploiement sur Render (gratuit)

Ce guide est spécifique à ton projet Laravel 12 + Livewire.

## Important avant de commencer

- Le **free web service Render se met en veille** après inactivité.
- Le **filesystem est éphémère** en free plan: les fichiers uploadés dans `storage/` peuvent disparaître lors des redéploiements/restarts.
- Pour les images/documents utilisateurs, privilégie un stockage externe (S3/R2/Cloudinary) en production.

---

## 1) Préparer le dépôt Git

Pousse le projet avec ces nouveaux fichiers:

- `Dockerfile`
- `render.yaml`
- `RENDER_DEPLOYMENT_GUIDE.md`

---

## 2) Créer le Blueprint Render

1. Render Dashboard > **New** > **Blueprint**
2. Connecte ton repo GitHub
3. Render détecte `render.yaml`
3. Valide la création des services:
   - `sanabot-web` (web)
   - `sanabot-db` (PostgreSQL free)

---

## 3) Variables d’environnement à renseigner

Dans `sanabot-web` (et idem pour worker/cron si non héritées):

- `APP_URL` = URL publique Render (ou domaine custom)
- `FEDAPAY_PUBLIC_KEY` = clé live
- `FEDAPAY_SECRET_KEY` = clé live
- `FEDAPAY_WEBHOOK_SECRET` = secret webhook

Déjà configuré dans `render.yaml` (version free):

- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=pgsql`
- `QUEUE_CONNECTION=sync`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`

---

## 4) Domaine personnalisé

1. Dans service `sanabot-web` > Settings > Custom Domain
2. Ajoute ton domaine (ex: `app.tondomaine.com`)
3. Configure le DNS (CNAME/A selon indications Render)
4. Active HTTPS (Render gère SSL)

---

## 5) Migrations et vérification

Le conteneur web exécute déjà:

- `php artisan migrate --force`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

Après 1er déploiement, vérifie:

- Login/inscription
- Catalogue cours
- Dashboard institution + pagination
- Jobs exécutés en synchrone (`QUEUE_CONNECTION=sync`)

## 6) Pourquoi pas de worker/cron en free

Sur Render, selon l'offre et la région, les services `worker` / `cron` peuvent ne pas être disponibles en free.
La version actuelle du blueprint est volontairement compatible free: **web + db uniquement**.

Si tu passes plus tard en plan payant, on pourra réactiver:

- un service `worker` pour `queue:work`
- un service `cron` pour `schedule:run`

---

## 7) Fedapay en production

Webhook à déclarer côté Fedapay:

```text
https://TON-DOMAINE/webhook/fedapay
```

Assure-toi que:

- `APP_URL` pointe bien vers ton domaine HTTPS
- `FEDAPAY_ENVIRONMENT=live`

---

## 8) Limites du free plan et solution recommandée

- Si tu veux éviter la veille et gagner en stabilité/performance: passe `sanabot-web` en plan paid.
- Si tu veux garder gratuit mais fiable pour les fichiers, externalise les uploads vers un bucket objet.

---

## 9) Commandes utiles (Render Shell)

```bash
php artisan optimize:clear
php artisan migrate --force
php artisan queue:retry all
php artisan queue:failed
```

---

Si tu veux, je peux te faire ensuite la configuration prête à coller pour **Cloudflare R2** afin que les uploads restent persistants en Render free.
