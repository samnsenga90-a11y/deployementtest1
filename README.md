# Nova Boutique — version locale XAMPP

Application e-commerce de démonstration en PHP 8+ / MariaDB (XAMPP). Elle comprend un catalogue, une fiche produit, un panier en session et une validation de commande avec enregistrement en base de données.

## Installation sous Windows 11 avec XAMPP

1. Copiez le contenu du dépôt dans `C:\xampp\htdocs\Projet-E_commerce\`.
2. Ouvrez XAMPP Control Panel et démarrez **Apache** et **MySQL**.
3. Ouvrez `http://localhost/phpmyadmin/`.
4. Cliquez sur **Importer**, sélectionnez `database/schema.sql`, puis lancez l'importation.
5. Vérifiez les paramètres de connexion dans `config.php`. La configuration initiale correspond à XAMPP standard (utilisateur `root`, mot de passe vide).
6. Ouvrez `http://localhost/Projet-E_commerce/`.

## Fonctionnalités

- Catalogue de produits depuis MariaDB.
- Fiche produit et disponibilité en stock.
- Panier conservé en session PHP, modification des quantités et suppression.
- Formulaire de commande avec coordonnées client.
- Enregistrement transactionnel des commandes et lignes de commande.
- Décrémentation du stock après validation.
- Protection CSRF et requêtes SQL préparées.
- Paiement à la livraison (aucune passerelle de paiement n'est intégrée).

## Configuration et limites

- Les produits fournis dans `database/schema.sql` sont des exemples : remplacez-les par vos produits, prix, images et stocks.
- La devise de démonstration est USD. Modifiez `CURRENCY` et `CURRENCY_SYMBOL` dans `config.php` selon vos besoins.
- Aucun compte client, tableau de bord administrateur, e-mail automatique, transporteur ou paiement en ligne n'est inclus dans cette première version.
- Le site est prévu pour un environnement local de test. Ne l'exposez pas directement à Internet sans durcissement, HTTPS, secrets hors dépôt, sauvegardes et revue de sécurité.
- Ne publiez jamais de vrais identifiants de base de données ou de mots de passe dans GitHub.

## Vérifications après installation

1. La page d'accueil affiche les produits.
2. Une fiche produit s'ouvre.
3. Ajouter un produit au panier puis modifier sa quantité fonctionne.
4. Une commande de test est enregistrée dans les tables `orders` et `order_items`.
5. Le stock du produit diminue après la commande.
6. Une commande avec un stock insuffisant est refusée.
