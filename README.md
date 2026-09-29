# API d'authentification — Laravel Sanctum

API REST d'authentification (inscription, connexion, déconnexion) sécurisée par un système de double jeton : un access token et un refresh token.

## Technologies

- Laravel 13
- PHP 8.3
- Laravel Sanctum (gestion des jetons)
- MySQL 8.4
- Environnement local : Laragon
- Tests : Postman et tests automatiques Laravel

## Fonctionnalités

- Inscription d'un utilisateur avec validation des données
- Connexion par email et mot de passe
- Consultation du profil de l'utilisateur connecté
- Renouvellement des jetons sans ressaisie du mot de passe
- Déconnexion de l'appareil courant ou de tous les appareils

## Routes de l'API

| Méthode | Route | Rôle | Jeton requis |
|---|---|---|---|
| POST | /api/register | Créer un compte | Aucun |
| POST | /api/login | Se connecter | Aucun |
| GET | /api/me | Consulter son profil | Access token |
| POST | /api/refresh | Renouveler les jetons | Refresh token |
| POST | /api/logout | Déconnexion de l'appareil courant | Access token |
| POST | /api/logout-all | Déconnexion de tous les appareils | Access token |

## Fonctionnement des jetons

| Jeton | Durée de vie | Rôle |
|---|---|---|
| Access token | 15 minutes | Accéder aux ressources protégées |
| Refresh token | 7 jours, usage unique | Obtenir une nouvelle paire de jetons |

Lorsque l'access token expire, l'API renvoie une erreur 401. Le client appelle alors la route de renouvellement avec le refresh token pour obtenir de nouveaux jetons.

## Mesures de sécurité

- Séparation des permissions : un access token ne peut pas renouveler les jetons, et un refresh token ne peut pas accéder aux ressources
- Rotation du refresh token : chaque refresh token n'est utilisable qu'une seule fois
- Suppression des anciens access tokens lors du renouvellement
- Expiration courte de l'access token
- Limitation des tentatives de connexion (5 par minute) contre les attaques par force brute
- Nettoyage automatique des jetons expirés
- Mots de passe et jetons chiffrés en base de données
- Mot de passe jamais renvoyé dans les réponses
- Message d'erreur neutre à la connexion, qui ne révèle pas si l'email existe
- Validation des données séparée du contrôleur (Form Requests)

## Codes de réponse

| Code | Signification |
|---|---|
| 200 | Requête réussie |
| 201 | Compte créé |
| 401 | Utilisateur non authentifié (jeton absent, invalide ou expiré) |
| 403 | Jeton sans la permission requise |
| 422 | Données invalides |
| 429 | Trop de tentatives de connexion |

## Tests réalisés

- Tests fonctionnels : inscription, connexion, profil, renouvellement, déconnexion
- Tests de sécurité : utilisation d'un jeton au mauvais endroit, jeton absent, inventé, expiré ou réutilisé, identifiants incorrects, email déjà utilisé, données invalides
- Tests automatiques couvrant les principaux scénarios d'authentification
