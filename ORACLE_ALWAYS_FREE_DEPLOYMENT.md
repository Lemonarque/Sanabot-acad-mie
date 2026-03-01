# Déploiement Oracle Cloud Always Free (Laravel 12)

Ce guide met ton app en production sur une VM Ubuntu gratuite Oracle (fiable), avec Nginx + PHP-FPM + MySQL + SSL Let's Encrypt.

---

## 0) Ce qu’il te faut avant de commencer

- Compte Oracle Cloud (Always Free)
- Domaine prêt (ex: `app.tondomaine.com`)
- Clé SSH locale (publique/privée)
- Le code du projet (ce repo)

---

## 1) Créer la VM Oracle (Always Free)

1. Oracle Cloud > Compute > Instances > Create Instance
2. Image: **Ubuntu 22.04**
3. Shape: **VM.Standard.A1.Flex** (Always Free)
4. CPU/RAM: 1 OCPU / 6 GB (ou 2 OCPU / 12 GB selon dispo)
5. Ajoute ta clé publique SSH
6. Network: VCN public + public IPv4

### Ouvrir les ports (Security List / NSG)

- `22` (SSH)
- `80` (HTTP)
- `443` (HTTPS)

---

## 2) Connexion SSH

Depuis ton PC:

```bash
ssh -i ~/.ssh/ta-cle.pem ubuntu@IP_PUBLIC_VM
```

---

## 3) Préparer le serveur

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx git unzip curl software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath
sudo apt install -y mysql-server
```

### Installer Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### Installer Node.js 20 + npm

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

---

## 4) Préparer MySQL

```bash
sudo mysql
```

Dans MySQL:

```sql
CREATE DATABASE sanabot_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sanabot_user'@'localhost' IDENTIFIED BY 'CHANGE_ME_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON sanabot_prod.* TO 'sanabot_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 5) Déployer l’application

> Exemple de dossier: `/var/www/sanabot`

```bash
sudo mkdir -p /var/www/sanabot
sudo chown -R ubuntu:ubuntu /var/www/sanabot
cd /var/www/sanabot
```

### Cloner le projet

```bash
git clone https://github.com/TON_COMPTE/TON_REPO.git .
```

### Installer dépendances PHP

```bash
composer install --no-dev --optimize-autoloader
```

### Configurer l’environnement

```bash
cp .env.production.example .env
php artisan key:generate
```

Édite `.env`:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://app.tondomaine.com`
- `DB_CONNECTION=mysql`
- `DB_DATABASE=sanabot_prod`
- `DB_USERNAME=sanabot_user`
- `DB_PASSWORD=...`
- `FILESYSTEM_DISK=public`
- `QUEUE_CONNECTION=database`
- `FEDAPAY_*` (live)

---

## 6) Build front + migrations + optimisations

```bash
npm install
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7) Permissions

```bash
sudo chown -R www-data:www-data /var/www/sanabot/storage /var/www/sanabot/bootstrap/cache
sudo chmod -R 775 /var/www/sanabot/storage /var/www/sanabot/bootstrap/cache
```

---

## 8) Configurer Nginx

Crée le fichier:

```bash
sudo nano /etc/nginx/sites-available/sanabot
```

Contenu:

```nginx
server {
    listen 80;
    server_name app.tondomaine.com;

    root /var/www/sanabot/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Activer le site:

```bash
sudo ln -s /etc/nginx/sites-available/sanabot /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 9) SSL Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d app.tondomaine.com
```

Vérifie le renouvellement auto:

```bash
sudo systemctl status certbot.timer
```

---

## 10) Queue + scheduler (production)

### Scheduler (cron)

```bash
crontab -e
```

Ajoute:

```cron
* * * * * cd /var/www/sanabot && php artisan schedule:run >> /dev/null 2>&1
```

### Queue worker (systemd)

```bash
sudo nano /etc/systemd/system/sanabot-queue.service
```

Contenu:

```ini
[Unit]
Description=Sanabot Laravel Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/sanabot/artisan queue:work --sleep=3 --tries=3 --max-time=3600
WorkingDirectory=/var/www/sanabot
StandardOutput=append:/var/www/sanabot/storage/logs/queue.log
StandardError=append:/var/www/sanabot/storage/logs/queue-error.log

[Install]
WantedBy=multi-user.target
```

Activer:

```bash
sudo systemctl daemon-reload
sudo systemctl enable sanabot-queue
sudo systemctl start sanabot-queue
sudo systemctl status sanabot-queue
```

---

## 11) Déploiement des mises à jour

À chaque update:

```bash
cd /var/www/sanabot
git pull
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx
sudo systemctl restart sanabot-queue
```

---

## 12) DNS (domaine)

Chez ton registrar:

- Crée un enregistrement `A`
- Nom: `app` (ou racine `@`)
- Valeur: `IP_PUBLIC_VM`

Attends la propagation DNS puis teste HTTPS.

---

## 13) Vérification finale

- Login fonctionne
- Pages institution/admin OK
- Upload images OK (`/storage/...`)
- Paiement Fedapay webhook configuré:
  - `https://app.tondomaine.com/webhook/fedapay`
- Queue worker actif (`systemctl status sanabot-queue`)

---

## 14) Sécurité minimale recommandée

```bash
sudo apt install -y ufw
sudo ufw allow OpenSSH
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

Optionnel mais recommandé:

```bash
sudo apt install -y fail2ban
```

---

Si tu veux, prochaine étape je te donne les commandes exactes avec tes vraies valeurs (domaine, repo Git, nom DB) pour un copier-coller direct.
