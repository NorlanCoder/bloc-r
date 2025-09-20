# Bloc-R - Système de Gestion des Militants

## 📋 Description

**Bloc-R** est une application web Laravel développée pour la gestion des militants d'un parti politique. L'application permet aux agents du parti d'enregistrer, gérer et suivre les militants à travers un système géographique organisé par départements, circonscriptions et communes.

## 🚀 Fonctionnalités Principales

### 👥 Gestion des Militants
- **Enregistrement** des nouveaux militants avec informations personnelles
- **Gestion des statuts** (actif, inactif, suspendu)
- **Suivi des paiements** (payé, impayé, en attente)
- **Système de vérification** (en cours, correct, refusé, corrigé)
- **Gestion des photos** de profil
- **Génération de cartes de référence** uniques

### 🗺️ Organisation Géographique
- **12 Départements** du Bénin
- **24 Circonscriptions** électorales
- **78 Communes** municipales
- **Relations hiérarchiques** entre les entités géographiques

### 🔐 Authentification et Sécurité
- **Système d'authentification** avec Laravel Sanctum
- **Gestion des tokens** API sécurisés
- **Contrôle d'accès** basé sur les rôles
- **Validation des données** robuste

### 📊 Suivi et Rapports
- **Filtrage avancé** par statut, paiement, vérification
- **Recherche** par nom, prénom, email, référence
- **Pagination** des résultats
- **Historique des opérations**

## 🛠️ Technologies Utilisées

- **Backend**: Laravel 12.x
- **Base de données**: MySQL
- **Authentification**: Laravel Sanctum
- **API**: RESTful API
- **Validation**: Laravel Validation
- **Storage**: Laravel Storage (photos)

## 📁 Structure du Projet

```
bloc-r/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/AuthController.php      # Authentification API
│   │   └── MilitantController.php      # Gestion des militants
│   └── Models/
│       ├── User.php                    # Modèle utilisateur
│       ├── Militant.php               # Modèle militant
│       ├── Departement.php            # Modèle département
│       ├── Circonscription.php        # Modèle circonscription
│       ├── Communes.php               # Modèle commune
│       ├── Paiement.php               # Modèle paiement
│       ├── Operation.php              # Modèle opération
│       └── Impression.php             # Modèle impression
├── database/
│   ├── migrations/                     # Migrations de base de données
│   └── seeders/                       # Données de référence
├── routes/
│   └── api.php                        # Routes API
└── storage/
    └── app/public/photos/             # Stockage des photos
```

## 🗄️ Modèle de Données

### Entités Principales

1. **Users** - Agents du parti
2. **Militants** - Membres du parti
3. **Departements** - Départements géographiques
4. **Circonscriptions** - Circonscriptions électorales
5. **Communes** - Communes municipales
6. **Paiements** - Historique des paiements
7. **Operations** - Journal des opérations
8. **Impressions** - Gestion des impressions

### Relations

- Un **User** peut gérer plusieurs **Militants**
- Un **Militant** appartient à un **Département**, une **Circonscription** et une **Commune**
- Une **Circonscription** appartient à un **Département**
- Une **Commune** appartient à une **Circonscription** et un **Département**

## 🔧 Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 5.7+
- Node.js (optionnel pour le frontend)

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone [url-du-repo]
cd bloc-r
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
Modifier le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bloc-r
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Exécuter les migrations et seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Démarrer le serveur**
```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 📚 Documentation API

Voir [API_DOCUMENTATION.md](./docs/API_DOCUMENTATION.md) pour la documentation complète de l'API.

## 🗃️ Base de Données

Voir [DATABASE_DOCUMENTATION.md](./docs/DATABASE_DOCUMENTATION.md) pour la documentation de la base de données.

## 🧪 Tests

```bash
# Exécuter tous les tests
php artisan test

# Exécuter les tests avec couverture
php artisan test --coverage
```

## 📝 Logs

Les logs de l'application sont stockés dans `storage/logs/laravel.log`

```bash
# Suivre les logs en temps réel
php artisan pail
```

## 🚀 Déploiement

### Production

1. **Optimiser l'application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Configurer le serveur web** (Apache/Nginx)

3. **Configurer les permissions**
```bash
chmod -R 755 storage bootstrap/cache
```

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👥 Équipe

- **Développeur Principal**: [Votre nom]
- **Email**: [votre.email@example.com]

## 📞 Support

Pour toute question ou problème, veuillez :
1. Vérifier la [documentation](./docs/)
2. Consulter les [issues](../../issues)
3. Créer une nouvelle issue si nécessaire

---

**Bloc-R** - Système de gestion des militants pour un parti politique moderne et efficace.