# Plateforme de gestion des livraisons

Laravel (admin Blade + API Sanctum) · MySQL · Flutter (application chauffeur)

## Prérequis
PHP 8.2+, Composer, MySQL (XAMPP), Node.js, Flutter SDK, Android Studio (émulateur).

## Installation du backend
```
composer install
copy .env.example .env
php artisan key:generate
```
Créer une base MySQL `Flash`, puis renseigner DB_DATABASE, DB_USERNAME et DB_PASSWORD dans `.env`.
```
php artisan migrate:fresh --seed
npm install
npm run dev          (dans un terminal)
php artisan serve    (dans un autre terminal)
```
Administration : http://localhost:8000

## Comptes de test (mot de passe : `password`)
| Rôle | Email |
|---|---|
| Admin | admin@test.com |
| Chauffeurs | karim@test.com, sara@test.com, youssef@test.com |
| Client | client@test.com |

## API (préfixe /api)
| Méthode | Route | Rôle |
|---|---|---|
| POST | /login | Connexion, retourne un token |
| POST | /logout | Révoque le token |
| GET | /mes-livraisons | Livraisons du chauffeur |
| GET | /livraisons/{id} | Détail d'une livraison |
| PATCH | /livraisons/{id}/statut | affectee > en_cours > livree |
| POST | /livraisons/{id}/note | Ajouter une note |

En-tête requis : `Accept: application/json`. Routes protégées : `Authorization: Bearer <token>`.

## Application Flutter (chauffeur)
```
cd flash delivery
flutter pub get
flutter run -d <id-emulateur>
```
Émulateur Android : l'API est jointe via `http://10.0.2.2:8000/api`.
Téléphone réel : remplacer l'adresse par l'IP locale du PC dans `lib/services/api_service.dart`
et lancer `php artisan serve --host=0.0.0.0 --port=8000`.

## Scénario de démonstration
1. Admin : créer une commande et l'affecter à Karim.
2. Chauffeur (mobile) : se connecter, démarrer la livraison, ajouter une note, marquer livrée.
3. Admin : rafraîchir, le statut est « livree », la note est visible, le dashboard est à jour.