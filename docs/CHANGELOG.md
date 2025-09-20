# Changelog - Bloc-R

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Versioning Sémantique](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-09-18

### Ajouté
- **Système d'authentification** avec Laravel Sanctum
- **Gestion des militants** avec CRUD complet
- **Organisation géographique** (12 départements, 24 circonscriptions, 78 communes)
- **Système de statuts** pour les militants (actif, inactif, suspendu)
- **Gestion des paiements** (payé, impayé, en attente)
- **Système de vérification** (en cours, correct, refusé, corrigé)
- **Gestion des photos** de profil des militants
- **Génération automatique** de cartes de référence
- **API RESTful** complète avec documentation
- **Système de filtrage** et recherche avancée
- **Pagination** des résultats
- **Validation robuste** des données
- **Relations géographiques** hiérarchiques
- **Seeders** pour les données de référence du Bénin
- **Migrations** de base de données complètes
- **Modèles Eloquent** avec relations
- **Contrôleurs API** optimisés
- **Documentation complète** (API, Base de données, Installation)

### Modèles
- `User` - Agents du parti
- `Militant` - Militants du parti
- `Departement` - Départements du Bénin
- `Circonscription` - Circonscriptions électorales
- `Communes` - Communes municipales
- `Paiement` - Historique des paiements
- `Operation` - Journal des opérations
- `Impression` - Gestion des impressions
- `Prix` - Tarifs et prix

### Contrôleurs
- `AuthController` - Authentification API
- `MilitantController` - Gestion des militants

### Routes API
- `POST /api/register` - Enregistrement d'agent
- `POST /api/login` - Connexion
- `POST /api/logout` - Déconnexion
- `GET /api/me` - Informations de l'agent connecté
- `GET /api/agents/militants` - Liste des militants
- `POST /api/agents/militants` - Créer un militant
- `GET /api/agents/militants/{id}` - Détails d'un militant
- `PUT /api/agents/militants/{id}` - Modifier un militant
- `DELETE /api/agents/militants/{id}` - Supprimer un militant
- `POST /api/agents/militants/{id}/accept` - Accepter une demande
- `POST /api/agents/militants/{id}/reject` - Rejeter une demande
- `GET /api/agents/militants/{agent_id}` - Militants d'un agent

### Base de Données
- **9 tables** principales
- **Relations FK** complètes
- **Index** optimisés pour les performances
- **Contraintes d'intégrité** robustes
- **Seeders** avec données réelles du Bénin

### Fonctionnalités Techniques
- **Laravel 12.x** avec PHP 8.2+
- **MySQL** avec migrations
- **Laravel Sanctum** pour l'authentification
- **Validation** Laravel native
- **Storage** pour les fichiers
- **Pagination** automatique
- **Relations Eloquent** optimisées
- **Scopes** de requête personnalisés
- **Accessors** et **Mutators**

### Documentation
- **README.md** - Vue d'ensemble du projet
- **API_DOCUMENTATION.md** - Documentation complète de l'API
- **DATABASE_DOCUMENTATION.md** - Documentation de la base de données
- **INSTALLATION_GUIDE.md** - Guide d'installation détaillé
- **CHANGELOG.md** - Journal des modifications

### Données de Référence
- **12 départements** du Bénin
- **24 circonscriptions** électorales
- **78 communes** municipales
- **Relations géographiques** correctes

## [0.1.0] - 2025-09-15

### Ajouté
- **Structure de base** du projet Laravel
- **Configuration initiale** de l'environnement
- **Migrations de base** pour les tables principales
- **Modèles de base** sans relations
- **Structure des contrôleurs**

### Modifié
- **Configuration** de la base de données
- **Structure** des migrations

### Corrigé
- **Problèmes de migration** avec table existante
- **Ordre des seeders** pour éviter les contraintes FK
- **Nommage des modèles** (Communes vs Commune)

---

## Types de Changements

- **Ajouté** pour les nouvelles fonctionnalités
- **Modifié** pour les changements de fonctionnalités existantes
- **Déprécié** pour les fonctionnalités qui seront supprimées
- **Supprimé** pour les fonctionnalités supprimées
- **Corrigé** pour les corrections de bugs
- **Sécurité** pour les vulnérabilités corrigées
