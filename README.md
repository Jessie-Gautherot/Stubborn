# Stubborn TP

## Présentation
Cette application Symfony est un TP.  
Il s'agit d'un site E-commerce proposant des sweatshirts à la vente.  
Il permet d'effectuer des achats fictifs grâce à Stripe en mode test (Sandbox).

## 1. Prérequis
Avant de commencer, assurez-vous d’avoir installé :

- PHP >= 8.2.12  
- Symfony CLI (v5.16.1)  
- Composer (https://getcomposer.org/)
- MySQL Community Server 8.4.8  
- Navigateur pour tester le site  
- Terminal compatible bash

## 2. Cloner le projet
```bash
git clone https://github.com/Jessie-Gautherot/Stubborn
cd Stubborn
```

## 3. Configuration de l'environnement

1. À l'aide du fichier `.env.example`, créez et configurez votre `.env.local`.

```bash
cp .env.example .env.local
```

2. Modifier `.env.local` :

- Adapter `DATABASE_URL` selon votre configuration locale.  

Exemple :

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/stubborn_db"
```

- Ajouter les clés Stripe en mode test fournies dans le rendu du devoir :

```env
STRIPE_SECRET_KEY="votre_clef_secrète_test"
STRIPE_PUBLISHABLE_KEY="votre_clef_publiable_test"
```
   
## 4. Installation automatisée 
Toutes les étapes d'installation sont automatisées via le script :

```bash
php project.php install
```

Cette commande :
  
- Installe les dépendances Composer  
- Créer la base de données  
- Execute les migrations
- Charge les fixtures 
  
## 5. Lancement du projet

```bash
php project.php start
```

Cette commande :

- Exécute automatiquement les tests unitaires (résultats affichés dans la console)  
- Démarre le serveur Symfony en arrière-plan  
- Application accessible sur http://localhost:8000  

## 6. Tests

Les tests sont automatiquement exécutés au lancement du projet via la commande `php project.php start`. 

## 7. Accès

Accédez à l’application via :  
http://localhost:8000

## 8. Connexion 

### Admin
- Email : admin1@example.com  
- Mot de passe : admin123  

### Client
- Email : bob@example.com  
- Mot de passe : password456  

## 9. Paiement Stripe (mode test)

- Les valeurs des variables STRIPE_SECRET_KEY et        STRIPE_PUBLISHABLE_KEY sont fournies dans le rendu du devoir  
- Aucun compte Stripe n’est nécessaire  
- Les paiements sont simulés en mode développement (sandbox)  


## 10. Faire un achat test

Utiliser une carte test Stripe :

- Numéro : 4242 4242 4242 4242  
- Date : n’importe quelle date future  
- CVC : 3 chiffres au choix  
- Code postal : au choix  

