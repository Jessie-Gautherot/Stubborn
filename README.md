# Stubborn TP

## Présentation
Cette application Symfony est un TP.  
Il s'agit d'un site E-commerce proposant des sweatshirts à la vente.  
Il permet d'effectuer des achats fictifs grâce à Stripe Sandbox.

## 1. Prérequis
Avant de commencer, assurez-vous d’avoir installé :

- PHP >= 8.2.12  
- Symfony CLI (v5.16.1)  
- Composer (https://getcomposer.org/)
- MySQL Community Server 8.4.8  
- Navigateur pour tester le site  

## 2. Cloner le projet
```bash
git clone https://github.com/Jessie-Gautherot/Stubborn
cd Stubborn
```

## 3. Installation 

Toutes les étapes d'installation sont automatisées grâce au **Makefile**.  
Composer doit être installé globalement.

Une seule commande permet de configurer le projet :

```bash
make install
```
Cette commande va automatiquement :
- Copier `.env.example` → `.env.local` (si pas déjà présent)  
- Installer des dépendances Composer  
- Créer et migrer la base MySQL `stubborn_db`  
- Charger les fixtures dev  
  
### IMPORTANT – Configuration Stripe

Avant de lancer l’application, assurez-vous que le fichier `.env.local` est bien basé sur `.env.example` puis renseignez les valeurs des clés Stripe dans `.env.local` :

- `STRIPE_SECRET_KEY`
- `STRIPE_PUBLISHABLE_KEY`

Ces valeurs sont fournies dans le rendu du devoir.

Sans ces clés, l’application fonctionne normalement, mais le paiement ne sera pas disponible.
  
### Base de données
Si la connexion à la base échoue, modifiez la variable `DATABASE_URL` dans le fichier `.env.local` avec vos identifiants MySQL.  
Par exemple :
```bash
DATABASE_URL="mysql://user:password@127.0.0.1:3306/stubborn_db"
```

## 4. Démarrage

```bash
make start
```

Cette commande :

- Exécute automatiquement les tests unitaires (résultats affichés dans la console)  
- Démarre le serveur Symfony en arrière-plan  
- Application accessible sur http://localhost:8000  

## 5. Tests

Les tests sont automatiquement exécutés au lancement du projet via la commande `make start`. 

## 6. Accès

Accédez à l’application via :  
http://localhost:8000

## 7. Connexion 

### Admin
- Email : admin1@example.com  
- Mot de passe : admin123  

### Client
- Email : bob@example.com  
- Mot de passe : password456  

## 8. Paiement Stripe (mode test)

- Les valeurs des variables STRIPE_SECRET_KEY et STRIPE_PUBLISHABLE_KEY de  `.env.local` doivent correspondre aux informations fournies dans le rendu du devoir  
- Aucun compte Stripe n’est nécessaire  
- Les paiements sont simulés en mode développement (sandbox)  


## 9. Faire un achat test

Utiliser une carte test Stripe :

- Numéro : 4242 4242 4242 4242  
- Date : n’importe quelle date future  
- CVC : 3 chiffres au choix  
- Code postal : au choix  

