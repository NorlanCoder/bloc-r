# Système de Rôles et Permissions - Bloc-R

## 🔐 Vue d'Ensemble

Le système Bloc-R implémente un système de rôles hiérarchique avec trois niveaux d'accès :

1. **Super Admin** - Accès complet au système
2. **Admin** - Gestion des demandes et paiements
3. **Agent** - Gestion des militants

## 👥 Rôles et Permissions

### 🔴 Super Admin (`super_admin`)

**Accès complet au système**

#### Gestion des Utilisateurs
- ✅ Ajouter des admins
- ✅ Lister tous les admins
- ✅ Activer/Désactiver des admins
- ✅ Lister tous les agents
- ✅ Activer/Désactiver des agents

#### Gestion des Demandes
- ✅ Voir toutes les demandes avec filtres avancés
- ✅ Refuser des demandes avec motif
- ✅ Marquer des cartes comme imprimées
- ✅ Réactiver l'impression d'une carte

#### Gestion des Prix
- ✅ Modifier le prix des cartes

#### Profil et Statistiques
- ✅ Modifier son profil
- ✅ Voir les statistiques générales

### 🟡 Admin (`admin`)

**Gestion des demandes et paiements**

#### Gestion des Utilisateurs
- ✅ Ajouter des admins
- ✅ Lister tous les admins

#### Gestion des Paiements
- ✅ Effectuer des paiements manuels

#### Gestion des Demandes
- ✅ Voir toutes les demandes avec filtres
- ✅ Refuser des demandes avec motif
- ✅ Marquer des cartes comme imprimées

#### Profil et Statistiques
- ✅ Modifier son profil
- ✅ Voir les statistiques

### 🟢 Agent (`agent`)

**Gestion des militants**

#### Gestion des Militants
- ✅ Lister les militants
- ✅ Ajouter des militants
- ✅ Voir les détails d'un militant
- ✅ Modifier un militant
- ✅ Supprimer un militant
- ✅ Accepter des demandes
- ✅ Rejeter des demandes
- ✅ Voir les militants d'un agent spécifique

## 🛡️ Middleware de Sécurité

### CheckRole Middleware

Le middleware `CheckRole` vérifie :
1. **Authentification** : L'utilisateur est-il connecté ?
2. **Statut actif** : Le compte est-il activé ?
3. **Rôle requis** : L'utilisateur a-t-il le bon rôle ?

```php
// Exemple d'utilisation
Route::middleware('role:super_admin')->group(function () {
    // Routes réservées aux super admins
});

Route::middleware('role:admin,super_admin')->group(function () {
    // Routes accessibles aux admins et super admins
});
```

## 📊 Structure des Données

### Table Users

| Champ | Type | Description |
|-------|------|-------------|
| `id` | bigint | Identifiant unique |
| `nom` | varchar(255) | Nom de famille |
| `prenom` | varchar(255) | Prénom |
| `email` | varchar(255) | Email (unique) |
| `password` | varchar(255) | Mot de passe hashé |
| `telephone` | varchar(255) | Numéro de téléphone |
| `photo` | varchar(255) | Chemin vers la photo |
| `role` | enum | `super_admin`, `admin`, `agent` |
| `is_active` | boolean | Compte activé/désactivé |
| `created_at` | timestamp | Date de création |
| `updated_at` | timestamp | Date de modification |

### Relations

```php
// User -> Militants (1:N)
$user->militants(); // Tous les militants d'un agent

// Scopes disponibles
User::byRole('admin')->get(); // Utilisateurs par rôle
User::active()->get(); // Utilisateurs actifs
```

## 🔧 Configuration

### Enregistrement du Middleware

Dans `bootstrap/app.php` :

```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
]);
```

### Utilisation dans les Routes

```php
// Routes pour super admin uniquement
Route::middleware('role:super_admin')->prefix('super-admin')->group(function () {
    // Routes super admin
});

// Routes pour admin et super admin
Route::middleware('role:admin,super_admin')->prefix('admin')->group(function () {
    // Routes admin
});

// Routes pour agent uniquement
Route::middleware('role:agent')->prefix('agents')->group(function () {
    // Routes agent
});
```

## 🚀 Utilisateurs par Défaut

Le système crée automatiquement des utilisateurs par défaut :

### Super Admin
- **Email** : `superadmin@bloc-r.com`
- **Mot de passe** : `password123`
- **Rôle** : `super_admin`

### Admin
- **Email** : `admin@bloc-r.com`
- **Mot de passe** : `password123`
- **Rôle** : `admin`

### Agent
- **Email** : `agent@bloc-r.com`
- **Mot de passe** : `password123`
- **Rôle** : `agent`

## 📝 Exemples d'Utilisation

### Connexion et Authentification

```bash
# Connexion en tant que super admin
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "superadmin@bloc-r.com",
    "password": "password123"
  }'
```

### Accès aux Routes Protégées

```bash
# Lister les admins (nécessite le rôle super_admin)
curl -X GET http://localhost:8000/api/super-admin/admins \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Gestion des Rôles

```bash
# Activer/Désactiver un admin
curl -X POST http://localhost:8000/api/super-admin/admins/1/toggle \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🔒 Sécurité

### Bonnes Pratiques

1. **Validation des Rôles** : Toujours vérifier le rôle avant d'accorder l'accès
2. **Logs d'Audit** : Toutes les actions sensibles sont loggées
3. **Tokens d'Accès** : Utilisation de Laravel Sanctum pour l'authentification
4. **Comptes Désactivés** : Les comptes désactivés ne peuvent pas se connecter

### Journalisation

Toutes les actions importantes sont enregistrées dans la table `operations` :

```php
Operation::create([
    'militant_id' => $militant->id,
    'type_operation' => 'admin_created',
    'description' => 'Super Admin a créé un nouvel admin',
    'details' => json_encode(['admin_id' => $admin->id, 'created_by' => Auth::id()])
]);
```

## 🧪 Tests

### Vérification des Rôles

```php
// Test de l'accès aux routes
$response = $this->actingAs($superAdmin)
    ->get('/api/super-admin/stats');
$response->assertStatus(200);

$response = $this->actingAs($agent)
    ->get('/api/super-admin/stats');
$response->assertStatus(403);
```

### Test des Middlewares

```php
// Test du middleware CheckRole
$middleware = new CheckRole();
$request = Request::create('/test', 'GET');
$request->setUserResolver(function () use ($user) {
    return $user;
});

$response = $middleware->handle($request, function ($req) {
    return response('OK');
}, 'super_admin');
```

## 📈 Évolutions Futures

### Rôles Personnalisés
- Création de rôles personnalisés
- Permissions granulaires
- Gestion des équipes

### Audit Avancé
- Historique des modifications
- Notifications en temps réel
- Rapports de sécurité

### Intégration
- SSO (Single Sign-On)
- LDAP/Active Directory
- OAuth2/OpenID Connect

---

**Bloc-R** - Système de rôles robuste et sécurisé pour une gestion efficace des permissions.
