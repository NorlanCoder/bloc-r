# Documentation API - Bloc-R

## 🌐 Base URL

```
http://localhost:8000/api
```

## 🔐 Authentification

L'API utilise Laravel Sanctum pour l'authentification. Tous les endpoints protégés nécessitent un token d'authentification dans l'en-tête `Authorization`.

```
Authorization: Bearer {token}
```

## 📋 Endpoints

### 🔑 Authentification

#### POST `/register`
Enregistrer un nouvel agent

**Paramètres:**
```json
{
    "nom": "string (required)",
    "prenom": "string (required)",
    "email": "string (required, unique)",
    "password": "string (required, min:8)",
    "telephone": "string (required, max:10)",
    "photo": "file (optional, image, max:2MB)"
}
```

**Réponse:**
```json
{
    "access_token": "string",
    "user": {
        "id": 1,
        "nom": "Doe",
        "prenom": "John",
        "email": "john@example.com",
        "telephone": "1234567890",
        "photo": "photos/photo.jpg",
        "created_at": "2025-09-18T20:00:00.000000Z",
        "updated_at": "2025-09-18T20:00:00.000000Z"
    }
}
```

#### POST `/login`
Connexion d'un agent

**Paramètres:**
```json
{
    "email": "string (required)",
    "password": "string (required)"
}
```

**Réponse:**
```json
{
    "access_token": "string",
    "user": {
        "id": 1,
        "nom": "Doe",
        "prenom": "John",
        "email": "john@example.com",
        "telephone": "1234567890",
        "photo": "photos/photo.jpg"
    }
}
```

#### POST `/logout` 🔒
Déconnexion de l'agent

**Réponse:**
```json
{
    "message": "Déconnexion réussie"
}
```

#### GET `/me` 🔒
Obtenir les informations de l'agent connecté

**Réponse:**
```json
{
    "id": 1,
    "nom": "Doe",
    "prenom": "John",
    "email": "john@example.com",
    "telephone": "1234567890",
    "photo": "photos/photo.jpg",
    "created_at": "2025-09-18T20:00:00.000000Z",
    "updated_at": "2025-09-18T20:00:00.000000Z"
}
```

### 👥 Gestion des Militants

#### GET `/agents/militants` 🔒
Lister tous les militants

**Paramètres de requête:**
- `status` (optional): Filtrer par statut (`active`, `inactive`, `suspended`)
- `status_paiement` (optional): Filtrer par statut de paiement (`paid`, `unpaid`, `pending`)
- `status_verification` (optional): Filtrer par statut de vérification (`en_cours`, `correct`, `refuse`, `corrige`)
- `search` (optional): Rechercher par nom, prénom, email ou référence
- `page` (optional): Numéro de page pour la pagination

**Exemple:**
```
GET /api/agents/militants?status=active&search=john&page=1
```

**Réponse:**
```json
{
    "data": [
        {
            "id": 1,
            "nom": "Doe",
            "prenom": "John",
            "email": "john@example.com",
            "telephone": "1234567890",
            "photo": "photos/militant1.jpg",
            "status": "active",
            "date_inscription": "2025-09-18T20:00:00.000000Z",
            "reference_carte": "BLR-2025-001",
            "status_paiement": "paid",
            "status_verification": "correct",
            "user": {
                "id": 1,
                "nom": "Agent",
                "prenom": "Admin"
            },
            "circonscription": {
                "code_circ": 1,
                "lib_circ": "1ERE CIRCONSCRIPTION ELECTORALE"
            },
            "departement": {
                "code_dep": 1,
                "lib_dep": "ALIBORI"
            },
            "commune": {
                "code_com": 1,
                "lib_com": "BANIKOARA"
            },
            "created_at": "2025-09-18T20:00:00.000000Z",
            "updated_at": "2025-09-18T20:00:00.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/agents/militants?page=1",
        "last": "http://localhost:8000/api/agents/militants?page=10",
        "prev": null,
        "next": "http://localhost:8000/api/agents/militants?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "per_page": 15,
        "to": 15,
        "total": 150
    }
}
```

#### POST `/agents/militants` 🔒
Créer un nouveau militant

**Paramètres:**
```json
{
    "nom": "string (required)",
    "prenom": "string (required)",
    "email": "string (required, unique)",
    "telephone": "string (optional)",
    "photo": "file (optional, image, max:2MB)",
    "circonscription_id": "integer (required)",
    "departement_id": "integer (required)",
    "commune_id": "integer (required)",
    "status_paiement": "string (optional, default: unpaid)",
    "motif_refus": "string (optional)"
}
```

**Réponse:**
```json
{
    "message": "Militant créé avec succès",
    "militant": {
        "id": 1,
        "nom": "Doe",
        "prenom": "John",
        "email": "john@example.com",
        "telephone": "1234567890",
        "photo": "photos/militant1.jpg",
        "status": "active",
        "date_inscription": "2025-09-18T20:00:00.000000Z",
        "reference_carte": "BLR-2025-001",
        "status_paiement": "unpaid",
        "status_verification": "en_cours",
        "user_id": 1,
        "circonscription_id": 1,
        "departement_id": 1,
        "commune_id": 1,
        "created_at": "2025-09-18T20:00:00.000000Z",
        "updated_at": "2025-09-18T20:00:00.000000Z"
    }
}
```

#### GET `/agents/militants/{id}` 🔒
Obtenir les détails d'un militant

**Réponse:**
```json
{
    "id": 1,
    "nom": "Doe",
    "prenom": "John",
    "email": "john@example.com",
    "telephone": "1234567890",
    "photo": "photos/militant1.jpg",
    "status": "active",
    "date_inscription": "2025-09-18T20:00:00.000000Z",
    "reference_carte": "BLR-2025-001",
    "status_paiement": "paid",
    "status_verification": "correct",
    "removed": "no",
    "motif_refus": null,
    "status_impression": "printed",
    "user": {
        "id": 1,
        "nom": "Agent",
        "prenom": "Admin"
    },
    "circonscription": {
        "code_circ": 1,
        "lib_circ": "1ERE CIRCONSCRIPTION ELECTORALE",
        "lib_iso": "1ERE"
    },
    "departement": {
        "code_dep": 1,
        "lib_dep": "ALIBORI"
    },
    "commune": {
        "code_com": 1,
        "lib_com": "BANIKOARA"
    },
    "created_at": "2025-09-18T20:00:00.000000Z",
    "updated_at": "2025-09-18T20:00:00.000000Z"
}
```

#### PUT `/agents/militants/{id}` 🔒
Mettre à jour un militant

**Paramètres:**
```json
{
    "nom": "string (optional)",
    "prenom": "string (optional)",
    "email": "string (optional, unique)",
    "telephone": "string (optional)",
    "photo": "file (optional, image, max:2MB)",
    "status": "string (optional)",
    "circonscription_id": "integer (optional)",
    "departement_id": "integer (optional)",
    "commune_id": "integer (optional)",
    "status_paiement": "string (optional)",
    "status_verification": "string (optional)",
    "motif_refus": "string (optional)"
}
```

**Réponse:**
```json
{
    "message": "Militant mis à jour avec succès",
    "militant": {
        "id": 1,
        "nom": "Doe",
        "prenom": "John",
        "email": "john@example.com",
        "telephone": "1234567890",
        "photo": "photos/militant1.jpg",
        "status": "active",
        "date_inscription": "2025-09-18T20:00:00.000000Z",
        "reference_carte": "BLR-2025-001",
        "status_paiement": "paid",
        "status_verification": "correct",
        "created_at": "2025-09-18T20:00:00.000000Z",
        "updated_at": "2025-09-18T20:00:00.000000Z"
    }
}
```

#### DELETE `/agents/militants/{id}` 🔒
Supprimer un militant

**Réponse:**
```json
{
    "message": "Militant supprimé avec succès"
}
```

#### POST `/agents/militants/{id}/accept` 🔒
Accepter une demande de militant

**Réponse:**
```json
{
    "message": "Demande acceptée avec succès",
    "militant": {
        "id": 1,
        "status_verification": "correct",
        "updated_at": "2025-09-18T20:00:00.000000Z"
    }
}
```

#### POST `/agents/militants/{id}/reject` 🔒
Rejeter une demande de militant

**Paramètres:**
```json
{
    "motif_refus": "string (required)"
}
```

**Réponse:**
```json
{
    "message": "Demande rejetée avec succès",
    "militant": {
        "id": 1,
        "status_verification": "refuse",
        "motif_refus": "Documents incomplets",
        "updated_at": "2025-09-18T20:00:00.000000Z"
    }
}
```

#### GET `/agents/militants/{agent_id}` 🔒
Obtenir les militants d'un agent spécifique

**Réponse:**
```json
{
    "data": [
        {
            "id": 1,
            "nom": "Doe",
            "prenom": "John",
            "email": "john@example.com",
            "status": "active",
            "reference_carte": "BLR-2025-001",
            "created_at": "2025-09-18T20:00:00.000000Z"
        }
    ],
    "meta": {
        "total": 1,
        "agent_id": 1
    }
}
```

## 📊 Codes de Statut

### Statuts des Militants
- `active` - Militant actif
- `inactive` - Militant inactif
- `suspended` - Militant suspendu

### Statuts de Paiement
- `paid` - Paiement effectué
- `unpaid` - Paiement en attente
- `pending` - Paiement en cours

### Statuts de Vérification
- `en_cours` - Vérification en cours
- `correct` - Vérification validée
- `refuse` - Vérification refusée
- `corrige` - Vérification corrigée

### Statuts d'Impression
- `printed` - Carte imprimée
- `not_printed` - Carte non imprimée

## ❌ Codes d'Erreur

### 400 - Bad Request
```json
{
    "message": "Les données fournies sont invalides",
    "errors": {
        "email": ["Le champ email est requis"],
        "password": ["Le mot de passe doit contenir au moins 8 caractères"]
    }
}
```

### 401 - Unauthorized
```json
{
    "message": "Non authentifié"
}
```

### 403 - Forbidden
```json
{
    "message": "Accès refusé"
}
```

### 404 - Not Found
```json
{
    "message": "Militant non trouvé"
}
```

### 422 - Unprocessable Entity
```json
{
    "message": "Erreur de validation",
    "errors": {
        "email": ["L'email est déjà utilisé"]
    }
}
```

### 500 - Internal Server Error
```json
{
    "message": "Erreur interne du serveur"
}
```

## 🔧 Exemples d'Utilisation

### cURL - Connexion
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

### cURL - Créer un militant
```bash
curl -X POST http://localhost:8000/api/agents/militants \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Doe",
    "prenom": "John",
    "email": "john@example.com",
    "telephone": "1234567890",
    "circonscription_id": 1,
    "departement_id": 1,
    "commune_id": 1
  }'
```

### JavaScript - Lister les militants
```javascript
fetch('http://localhost:8000/api/agents/militants', {
    headers: {
        'Authorization': 'Bearer YOUR_TOKEN',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

## 📝 Notes Importantes

1. **Authentification**: Tous les endpoints marqués avec 🔒 nécessitent une authentification
2. **Pagination**: Les listes sont paginées par défaut (15 éléments par page)
3. **Validation**: Tous les champs sont validés côté serveur
4. **Photos**: Les photos sont stockées dans `storage/app/public/photos/`
5. **Références**: Les cartes de référence sont générées automatiquement
6. **Relations**: Les relations géographiques sont validées (commune → circonscription → département)

## 🔄 Versioning

L'API utilise la versioning par URL. La version actuelle est v1 (implicite).

Exemple: `http://localhost:8000/api/v1/agents/militants`
