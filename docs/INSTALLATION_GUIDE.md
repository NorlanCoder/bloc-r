# Guide d'Installation - Bloc-R

## 📋 Prérequis

Avant de commencer l'installation, assurez-vous d'avoir les éléments suivants installés sur votre système :

### Logiciels Requis

- **PHP** 8.2 ou supérieur
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL** 5.7 ou supérieur (ou MariaDB 10.3+)
- **Node.js** 16+ (optionnel, pour le frontend)
- **Git** (pour cloner le projet)

### Extensions PHP Requises

- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## 🚀 Installation Complète

### Étape 1 : Cloner le Projet

```bash
# Cloner le repository
git clone [URL_DU_REPOSITORY] bloc-r
cd bloc-r
```

### Étape 2 : Installation des Dépendances

```bash
# Installer les dépendances PHP
composer install

# Installer les dépendances Node.js (optionnel)
npm install
```

### Étape 3 : Configuration de l'Environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### Étape 4 : Configuration de la Base de Données

1. **Créer la base de données MySQL :**

```sql
CREATE DATABASE bloc_r CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Configurer le fichier `.env` :**

```env
APP_NAME="Bloc-R"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bloc_r
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Étape 5 : Exécution des Migrations et Seeders

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter les seeders pour les données de référence
php artisan db:seed
```

### Étape 6 : Configuration du Stockage

```bash
# Créer le lien symbolique pour le stockage
php artisan storage:link

# Créer les dossiers nécessaires
mkdir -p storage/app/public/photos
chmod -R 755 storage
```

### Étape 7 : Démarrer l'Application

```bash
# Démarrer le serveur de développement
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 🔧 Installation sur Différents Systèmes

### Windows

#### Avec XAMPP

1. **Télécharger et installer XAMPP**
2. **Démarrer Apache et MySQL**
3. **Ouvrir le terminal dans le dossier du projet**
4. **Suivre les étapes 2-7 ci-dessus**

#### Avec WAMP

1. **Télécharger et installer WAMP**
2. **Démarrer les services**
3. **Ouvrir le terminal dans le dossier du projet**
4. **Suivre les étapes 2-7 ci-dessus**

### Linux (Ubuntu/Debian)

```bash
# Mettre à jour le système
sudo apt update && sudo apt upgrade -y

# Installer PHP et extensions
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl

# Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer MySQL
sudo apt install mysql-server

# Installer Node.js (optionnel)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Suivre les étapes 2-7 de l'installation
```

### macOS

```bash
# Installer Homebrew (si pas déjà installé)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Installer PHP
brew install php@8.2

# Installer Composer
brew install composer

# Installer MySQL
brew install mysql
brew services start mysql

# Installer Node.js (optionnel)
brew install node

# Suivre les étapes 2-7 de l'installation
```

## 🐳 Installation avec Docker

### Dockerfile

```dockerfile
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Configurer les permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: bloc-r-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - bloc-r

  nginx:
    image: nginx:alpine
    container_name: bloc-r-nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - bloc-r

  mysql:
    image: mysql:8.0
    container_name: bloc-r-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: bloc_r
      MYSQL_ROOT_PASSWORD: root
      MYSQL_USER: bloc_r
      MYSQL_PASSWORD: password
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - bloc-r

volumes:
  mysql_data:
    driver: local

networks:
  bloc-r:
    driver: bridge
```

### Commandes Docker

```bash
# Construire et démarrer les conteneurs
docker-compose up -d

# Exécuter les migrations
docker-compose exec app php artisan migrate

# Exécuter les seeders
docker-compose exec app php artisan db:seed

# Arrêter les conteneurs
docker-compose down
```

## 🔧 Configuration Avancée

### Configuration Apache

```apache
<VirtualHost *:80>
    ServerName bloc-r.local
    DocumentRoot /path/to/bloc-r/public
    
    <Directory /path/to/bloc-r/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/bloc-r_error.log
    CustomLog ${APACHE_LOG_DIR}/bloc-r_access.log combined
</VirtualHost>
```

### Configuration Nginx

```nginx
server {
    listen 80;
    server_name bloc-r.local;
    root /path/to/bloc-r/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Configuration de Production

```bash
# Optimiser l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurer les permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Configurer le serveur web pour pointer vers public/
```

## 🧪 Tests et Validation

### Vérification de l'Installation

```bash
# Vérifier la configuration PHP
php artisan about

# Tester la connexion à la base de données
php artisan tinker
>>> DB::connection()->getPdo();

# Vérifier les routes
php artisan route:list

# Exécuter les tests
php artisan test
```

### Tests de Fonctionnalité

```bash
# Tester l'API d'authentification
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"nom":"Test","prenom":"User","email":"test@example.com","password":"password123","telephone":"1234567890"}'

# Tester la liste des militants
curl -X GET http://localhost:8000/api/agents/militants \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🚨 Dépannage

### Problèmes Courants

#### Erreur de Permissions

```bash
# Corriger les permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

#### Erreur de Base de Données

```bash
# Vérifier la connexion
php artisan tinker
>>> DB::connection()->getPdo();

# Recréer la base de données
php artisan migrate:fresh --seed
```

#### Erreur de Composer

```bash
# Nettoyer le cache Composer
composer clear-cache
composer install --no-cache
```

#### Erreur de Storage Link

```bash
# Supprimer et recréer le lien
rm public/storage
php artisan storage:link
```

### Logs et Debug

```bash
# Voir les logs en temps réel
php artisan pail

# Vider les logs
php artisan log:clear

# Mode debug
# Dans .env : APP_DEBUG=true
```

## 📚 Ressources Supplémentaires

- [Documentation Laravel](https://laravel.com/docs)
- [Documentation Composer](https://getcomposer.org/doc/)
- [Documentation MySQL](https://dev.mysql.com/doc/)
- [Documentation PHP](https://www.php.net/docs.php)

## 🤝 Support

Si vous rencontrez des problèmes lors de l'installation :

1. Vérifiez les logs dans `storage/logs/laravel.log`
2. Consultez la [documentation API](./API_DOCUMENTATION.md)
3. Consultez la [documentation de la base de données](./DATABASE_DOCUMENTATION.md)
4. Créez une issue sur le repository du projet

---

**Bloc-R** - Installation et configuration simplifiées pour un déploiement rapide.
